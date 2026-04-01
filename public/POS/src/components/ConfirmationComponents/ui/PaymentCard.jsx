import React, { useState, useEffect, useRef, useContext } from "react";
import { MdOutlineAttachMoney, MdFlashOn } from "react-icons/md";
import { BsFillCreditCardFill } from "react-icons/bs";
import { FaWallet } from "react-icons/fa6";
import { FaRegMoneyBillAlt } from "react-icons/fa";
import { FormDataContext } from "../../../contexts/FormDataContext";
import { useSelectedProducts } from "../../../contexts/SelectedProductsContext";
export default function PaymentCard({
  error,
  remainingValue,
  setRemainingValue,
}) {
  const { total } = useSelectedProducts();
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
  const {selectedProducts} = useSelectedProducts()
  
  const { formData, setFormData } = useContext(FormDataContext);
  const [selectedMethods, setSelectedMethods] = useState(
    formData.paymentMethods,
  );
  const inputRefs = useRef({});
    const userSettings = JSON.parse(localStorage.getItem("User Settings"))
useEffect(()=>{
if(selectedProducts.length === 0) {
  setSelectedMethods({});
  setFormData((prev) => ({
    ...prev,
    paymentMethods: {}
  }))
}
}, [selectedProducts, setFormData])
  
  useEffect(() => {
    if (!formData?.paymentMethod) return;

    setSelectedMethods((prev) => {
      // const updated = Object.keys(prev).length === 1 ? {} : { ...prev };
      const updated = { ...prev };
      const method = formData.paymentMethod;

      const otherMethodsTotal = Object.entries(updated)
        .filter(([key]) => key !== method)
        .reduce((sum, [, val]) => sum + Number(val || 0), 0);
      const remainingAmount = total - otherMethodsTotal.toFixed(2);
      if (updated[method] !== undefined) {
        // if (Number(updated[method]) < remainingAmount) {
          updated[method] = remainingAmount.toFixed(2);
        // }
      } else {
        updated[method] = remainingAmount.toFixed(2);
      }
      return updated;
    });
  }, [formData?.paymentMethod, total, remainingValue]);




  useEffect(() => {
    if (Object.keys(selectedMethods).length === 1) {
      setFormData((prev) => ({
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
    setSelectedMethods((prev) => ({ ...prev, [name]: value }));
  };

  // Calculate remaining total dynamically
  const paidAmount = Object.values(selectedMethods).reduce(
    (sum, val) => sum + Number(val || 0),
    0,
  );


  const remainingTotal = (paidAmount - total).toFixed(2);
  const isFullyPaid = Number(remainingTotal) < 0;
  useEffect(() => {
    setRemainingValue(remainingTotal);
  }, [remainingTotal ]);

  return (
    <div className="w-full flex flex-col gap-1">
      <div
        className={`font-semibold text-end ${isFullyPaid ? `text-red-600 font-bold` : ``}`}
      >
        المتبقي:
        {remainingTotal}
      </div>
      {methods.map((m) => (
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
              disabled={!userSettings?.methodChangeAuth}
            />
            <span className="text-blue-600 opacity-80 cursor-pointer" onClick={()=> handleToggle(m.name)}>{m.icon}</span>
            <span className="text-sm font-semibold cursor-pointer" onClick={()=> handleToggle(m.name)}>{m.name}</span>
          </div>

          {selectedMethods[m.name] !== undefined && (
            <input
              ref={(el) => (inputRefs.current[m.name] = el)}
              type="text"
              value={selectedMethods[m.name] ?? ""}
              onChange={(e) => handleAmountChange(m.name, e.target.value)}
              onBlur={() => {
                if (selectedMethods[m.name] === "") {
                  setSelectedMethods((prev) => {
                    const copy = { ...prev };
                    delete copy[m.name];
                    return copy;
                  });
                }
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
