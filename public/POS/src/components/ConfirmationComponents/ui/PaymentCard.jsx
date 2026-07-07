import React, { useState, useEffect, useRef, useContext } from "react";
import { MdOutlineAttachMoney, MdFlashOn } from "react-icons/md";
import { BsFillCreditCardFill } from "react-icons/bs";
import { FaWallet } from "react-icons/fa6";
import { FaRegMoneyBillAlt } from "react-icons/fa";
import { FormDataContext } from "../../../contexts/FormDataContext";
import { useSelectedProducts } from "../../../contexts/SelectedProductsContext";
import { fetchConfigs } from "../../../store/reducers/settingSlice";
import { useSelector, useDispatch } from "react-redux";
import { useProducts } from "../../../contexts/ProductsContext";
export default function PaymentCard({
  error,
  remainingValue,
  setRemainingValue,
}) {
  const { total } = useSelectedProducts();
  const permissions = useSelector((state) => state?.setting?.permissions);
  // const invoiceSettings = JSON.parse(localStorage.getItem("Invoice Settings"))
  const dispatch = useDispatch();
  const invoiceSettings = useSelector(
    (state) => state?.setting?.invoiceSettings,
  );
  // useEffect(() => {
  //   //dispatch(fetchConfigs());
  // }, [dispatch]);

  const paymentMethodOptions = [
    { id: 1, code: "cash", label: "كاش" },
    { id: 2, code: "credit_card", label: "بطاقة ائتمان" },
    { id: 3, code: "instapay", label: "انستا باى" },
    { id: 4, code: "wallet", label: "محفظة" },
    { id: 5, code: "remaining", label: "اجل" },
  ];

  const allowedPaymentMethodCodes = invoiceSettings?.allowedPaymentMethods || [
    "كاش",
  ];
  const allowedPaymentMethodLabels = paymentMethodOptions
    .filter((method) => allowedPaymentMethodCodes.includes(method.code))
    .map((method) => method.label);

  const allowedPaymentMethods = allowedPaymentMethodLabels || ["كاش"];
  const methods = [
    { id: 1, name: "كاش", icon: <MdOutlineAttachMoney />, desc: "دفع نقدى" },
    {
      id: 2,
      name: "بطاقة ائتمان",
      icon: <BsFillCreditCardFill />,
      desc: "فيزا - ماستركاد",
    },
    { id: 3, name: "محفظة", icon: <FaWallet />, desc: "محفظة إلكترونية" },
    { id: 4, name: "انستا باى", icon: <MdFlashOn />, desc: "انستا باى" },
    { id: 5, name: "اجل", icon: <FaRegMoneyBillAlt />, desc: "الدفع لاحقاً" },
  ];
  const visibleMethods = methods.filter((method) =>
    allowedPaymentMethods?.includes(method.name),
  );
  const { selectedProducts } = useSelectedProducts();

  const { formData, setFormData } = useContext(FormDataContext);
  const { setDraftFormData } = useProducts();

  const [selectedMethods, setSelectedMethods] = useState(
    formData.paymentMethods,
  );
  const inputRefs = useRef({});
  const userSettings = JSON.parse(localStorage.getItem("User Settings"));
  useEffect(() => {
    if (selectedProducts.length === 0) {
      setSelectedMethods({});
      setFormData((prev) => ({
        ...prev,
        paymentMethods: {},
      }));
      setDraftFormData((prev) => ({
        ...prev,
        paymentMethods: {},
      }));
    }
  }, [selectedProducts, setFormData, setDraftFormData]);

  useEffect(() => {
    if (!formData?.paymentMethod) return;

    setSelectedMethods((prev) => {
      // const updated = Object.keys(prev).length === 1 ? {} : { ...prev };
      const updated = { ...prev };
      const method = formData.paymentMethod;

      const otherMethodsTotal = Object.entries(updated)
        .filter(([key]) => key !== method)
        .reduce((sum, [, val]) => sum + Number(val || 0), 0);
      const remainingAmount = Number(total) - otherMethodsTotal.toFixed(2);
      if (updated[method] !== undefined) {
        // if (Number(updated[method]) < remainingAmount) {
        updated[method] = remainingAmount.toFixed(2);
        // }
      } else {
        updated[method] = remainingAmount.toFixed(2);
      }
      return updated;
    });
  }, [formData?.paymentMethod, total]);

  useEffect(() => {
    if (Object.keys(selectedMethods).length === 1) {
      setFormData((prev) => ({
        ...prev,
        paymentMethod: `${Object.keys(selectedMethods)[0]}`,
      }));
      setDraftFormData((prev) => ({
        ...prev,
        paymentMethod: `${Object.keys(selectedMethods)[0]}`,
      }));
    }
  }, [selectedMethods]);

  useEffect(() => {
    if (Object.keys(selectedMethods).length > 0) {
      setFormData((prev) => ({
        ...prev,
        paymentMethods: selectedMethods,
      }));
      setDraftFormData((prev) => ({
        ...prev,
        paymentMethods: selectedMethods,
      }));
    }
  }, [selectedMethods]);
  const handleToggle = (name) => {
    setSelectedMethods((prev) => {
      const copy = { ...prev };
      if (copy[name] !== undefined) {
        delete copy[name];
      } else {
        copy[name] = "";
        setTimeout(() => inputRefs.current[name]?.focus(), 0);
      }
      return copy;
    });
  };

  const handleAmountChange = (name, value) => {
  if (!/^\d*\.?\d*$/.test(value)) return;

  const defaultMethod = formData.paymentMethod || "كاش";

  setSelectedMethods((prev) => {
    const updated = { ...prev, [name]: value };

    if (name === defaultMethod) {
      return updated;
    }

    const otherMethodsTotal = Object.entries(updated)
      .filter(([key]) => key !== defaultMethod)
      .reduce((sum, [, val]) => sum + Number(val || 0), 0);

    const remainingForDefault = Number(total) - otherMethodsTotal;

    if (remainingForDefault >= 0) {
      updated[defaultMethod] = remainingForDefault.toString();
    } else {
      updated[defaultMethod] = "0"; 
    }

    return updated;
  });
};

  const paidAmount = Object.values(selectedMethods).reduce(
    (sum, val) => sum + Number(val || 0),
    0,
  );

  const remainingTotal = (paidAmount - Number(total)).toFixed(2);
  const isFullyPaid = Number(remainingTotal) < 0;
  useEffect(() => {
    setRemainingValue(remainingTotal);
  }, [remainingTotal]);

  return (
    <div className="w-full flex flex-col gap-1">
      <div
        className={`font-semibold text-end ${isFullyPaid ? `text-red-600 font-bold` : ``}`}
      >
        المتبقي:
        {remainingTotal}
      </div>
      {visibleMethods.map((m) => (
        <div
          key={m.id}
          className={`flex items-center justify-between border rounded-md p-1 px-2 transition ${
            selectedMethods[m.name]
              ? "bg-blue-50 border-blue-500"
              : "bg-neutral-primary-soft border-gray-300"
          }`}
        >
          <div className="flex items-center gap-1">
            <input
              type="checkbox"
              checked={!!selectedMethods[m.name]}
              onChange={() => handleToggle(m.name)}
              className="w-4 h-4 accent-blue-600"
              disabled={!permissions["pos.paymentMethodChangeAuth"]}
            />
            <span
              className="text-blue-600 opacity-80 cursor-pointer"
              onClick={() => handleToggle(m.name)}
            >
              {m.icon}
            </span>
            <span
              className="text-sm font-semibold cursor-pointer"
              onClick={() => handleToggle(m.name)}
            >
              {m.name}
            </span>
          </div>

          {selectedMethods[m.name] !== undefined && (
            <input
              ref={(el) => (inputRefs.current[m.name] = el)}
              type="text"
              value={selectedMethods[m.name] ?? ""}
              onChange={(e) => handleAmountChange(m.name, e.target.value)}
              onBlur={() => {
                setSelectedMethods((prev) => {
                  const copy = { ...prev };

                  if (copy[m.name] === "" || copy[m.name] === undefined) {
                    delete copy[m.name];
                  } else {
                    copy[m.name] = Number(copy[m.name]).toFixed(2);
                  }
                  return copy;
                });
              }}
              placeholder="المبلغ"
              className="w-3/4 text-sm p-1 border-2 border-blue-600 rounded-md focus:outline-blue-600 focus:ring-1 focus:ring-blue-500"
            />
          )}
        </div>
      ))}
      {error && (
        <span className="text-red-600 text-sm font-semibold">{error}</span>
      )}
    </div>
  );
}
