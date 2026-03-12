import { useState, useEffect, useRef, useContext } from "react";
import { useSelector, useDispatch } from "react-redux";
import { useNavigate } from "react-router-dom";
import { FormDataContext } from "../../contexts/FormDataContext";
import { useSelectedProducts } from "../../contexts/SelectedProductsContext";
import { saveProductsOfflineThunk } from "../../store/reducers/productSlice";
import { saveDraft } from "../../store/reducers/draftSlice";
import { setSelectedUser } from "../../store/reducers/userSlice";
import notify from "../Notification";
import { number } from "yup";
export default function useProductsLogic() {
  const data = useSelector((state) => state?.product?.products); // All Products Fetched From the Backend
  const users = useSelector((state) => state?.user?.users); // Users Fetched From the Backend

  const dispatch = useDispatch();
  
  const [draftData, setDraftData] = useState(null);
  const [searchClientName, setSearchClientName] = useState(""); // State Which Searches For The Client By Name
  const [searchClientPhone, setSearchClientPhone] = useState(""); // State Which Searches For The Client By Phone Number
  const [searchValue, setSearchValue] = useState(""); // State Which Passes The KeyPad Value To The BarCode Input
  const [searchNameValue, setSearchNameValue] = useState(""); // State Which Hold The SEarched Value Of Search By Name Input
  const [searchNameValueInGrid, setSearchNameValueInGrid] = useState(""); // State Which Hold The Searched Value Of Search By Name Input (in Grid)
  const [searchResults, setSearchResults] = useState([]); // State Which Holds The Returned Data Of Searched By Name Input
  const [searchClientNamesResult, setSearchClientNamesResult] = useState([]); // State Which Holds The Returned Data Of Searched By Name Input
  const [searchClientPhoneResult, setSearchClientPhoneResult] = useState([]); // State Which Holds The Returned Data Of Searched By Phone Input
  const [draftFormData, setDraftFormData] = useState(null); // State Which Holds The Values Of The Form Stored in Drafts
  const [weight, setWeight] = useState(0); // State Which Holds The Values Of The Form Stored in Drafts
  const [selectedCategory, setSelectedCategory] = useState(1); // State Which Hold The Value Of The Selcted Category
  const [currentPage, setCurrentPage] = useState(1); // State Which Holds The Value Of The Current Page
  const [isPopupOpen, setIsPopupOpen] = useState(false);  //State Which Holds The Value of The Pop-up
  const [isSearchPopupOpen, setIsSearchPopupOpen] = useState(false);  //State Which Holds The value of The Search in The Pop-up
  const [invSerial, setInvSerial] = useState(1);  //State Which Holds The value of The Invoice Serial

  const inputNameRef = useRef(null); // Input Ref To The Search By Name Input
  const formRef = useRef(); // Input Ref To The Form
  const inputRef = useRef(null); // Input Ref To The BarCode Input
  const phoneSearchRef = useRef(null); // Input Ref To The BarCode Input

  const navigate = useNavigate();

  const { formData, setFormData } = useContext(FormDataContext); // Form Context
  const { selectedProducts, setSelectedProducts, total } =
    useSelectedProducts(); // Selected Products Context

  {
    /* Array Of The Selected Category */
  }
  const selectedCategoryObj = data.find((item) => {
    return item.id === selectedCategory;
  });

  {
    /* Array Of The Products Of The Selected Category */
  }
  const filteredProducts = selectedCategoryObj
    ? selectedCategoryObj.products
    : [];

  {
    /* Array Of Products Filtered By Name */
  }
  const filteredProductsByName = filteredProducts.filter((p) =>
    p.name.toLowerCase().includes(searchNameValueInGrid.toLowerCase()),
  );

  {
    /* Fetching The Products From The BackEnd */
  }

  useEffect(() => {
    dispatch(saveProductsOfflineThunk(data));
  }, [dispatch]);

   {
    /* Adding A New Item To The Table */
  }

  const handleAddObj = (newObj) => {
    if (formData.serialInput.length !== 0) {
      setSelectedProducts((prevProducts) => {
        const existsIndex = prevProducts.findIndex(
          (p) =>
            p.id === newObj.id && p.baseUnit.value === newObj.baseUnit.value,
        );

        if ((newObj.is_stock || newObj.is_weight) && newObj.quantity <= 0) {
          notify("لا يوجد رصيد للمنتج", "warn");
          return prevProducts;
        }

        if (newObj.is_weight) {
          if (existsIndex !== -1) {
            return prevProducts.map((product, idx) => {
              if (idx !== existsIndex) return product;

              if (newObj.weight <= product.quantity) {
                // normal update
                return {
                  ...product,
                  number: product.number + 1,
                  weight: product.weight + newObj.weight,
                  quantity: product.quantity - newObj.weight,
                  total: (product.weight + newObj.weight) * product.price,
                };
              } else {
                notify("الكمية لا تسمح", "warn");
                return product;
              }
            });
          }

          if (newObj.weight > newObj.quantity) {
            notify("الكمية لا تسمح", "warn");
            return [
              ...prevProducts,
              {
                ...newObj,
                weight: newObj.quantity,
                quantity: newObj.quantity - newObj.weight,
                total: newObj.quantity * newObj.price,
                stock: newObj.stock,
              },
            ];
          } else {
            return [
              ...prevProducts,
              {
                ...newObj,
                weight: newObj.weight,
                quantity: newObj.quantity - newObj.weight,
                total: newObj.weight * newObj.price,
              },
            ];
          }
        }

        const nextNumber =
          existsIndex !== -1 ? prevProducts[existsIndex].number + 1 : 1;

        if (newObj.is_stock && nextNumber > newObj.quantity) {
          notify("الكمية لا تسمح", "warn");
          return prevProducts;
        }

        if (existsIndex !== -1) {
          return prevProducts.map((product, idx) =>
            idx === existsIndex
              ? {
                  ...product,
                  number: nextNumber,
                  total: nextNumber * product.price,
                  canDecrement: nextNumber > 1,
                  quantity: product.quantity - 1,
                }
              : product,
          );
        }

        return [
          ...prevProducts,
          {
            ...newObj,
            number: 1,
            total: newObj.price,
            canDecrement: false,
            quantity: newObj.quantity - 1,
          },
        ];
      });

      focusInput();
    } else {
      notify("يجب كتابة مسلسل للفاتورة", "warn");
    }
  };

  {
    /* Function To Delete A Product */
  }
  const handleDelete = (rowKey) => {
    setSelectedProducts((prev) =>
      prev.filter((product) => product.rowKey !== rowKey),
    );
  };

  {
    /* Function To Focus On The BarCode Input */
  }
  const focusInput = () => {
    inputRef.current?.focus();
  };

  {
    /* Function To Change The Quantity Of A Product */
  }
  const ChangeQuantity = (rowKey, newVal) => {
    setSelectedProducts((prevProducts) =>
      prevProducts.map((product) => {
        if (product.rowKey !== rowKey) return product;

        // If input is empty or invalid, default to 1
        let value = parseFloat(newVal);
        if (isNaN(value) || value < 0) {
          value = 1;
        }

        const finalQuantity =
          !product?.is_weight && !product?.is_stock
            ? value
            : Math.min(value, product.stock);
        if(product.is_weight || product.is_stock){
          if (value > product.stock) {
          notify("الكمية لا تسمح", "warn");
        }
        }
        return {
          ...product,
          quantity: product.quantity - finalQuantity,
          number: finalQuantity,
          weight: finalQuantity,
          canDecrement: finalQuantity > 1,

          total: product.price * finalQuantity,
        };
      }),
    );
  };

  {
    /* Function To Change The Unit Of A Product */
  }
  const handleChangeUnit = (productId, option) => {
    setSelectedProducts((prevProducts) => {
      const unitId = option.unitData.id;
      const newPrice = Number(option.unitData.sallprice);
      const newCode = option?.unitData?.barcodes[0]?.code || "";
      const existingIndex = prevProducts.findIndex(
        (p) => p.id === productId && p.baseUnit?.value === unitId,
      );

      if (existingIndex !== -1) {
        return prevProducts.map((product, idx) => {
          if (idx === existingIndex) {
            return {
              ...product,
              weight: product.is_weight ? product.quantity : 0.0,
      stock: product.quantity,
      is_weight: product.is_weight,
      is_stock: product.is_stock,
      quantity: product?.quantity || 0,
              baseUnit: {
                value: unitId,
                label: `${option?.unitData.name} - ${newPrice}${product?.unit_name}`,
                unitData: option?.unitData,
              },
              price: newPrice,
              total: newPrice * product?.number,
              code: newCode,
              rowKey: `${product.id}-${unitId}`,
              selectedUnit: {
                ...product?.selectedUnit,
                [unitId]: {
                  value: unitId,
                  label: option?.unitData.name,
                  unitData: option?.unitData,
                },
              },
            };
          }
          



          return product;
        });
      }
      const baseProduct = prevProducts.find((p) => p.id === productId);
      if (!baseProduct) return prevProducts;
      const newRow = {
        id: baseProduct.id,
        name: baseProduct.name,
        image: baseProduct.image,
        unit_name: baseProduct.unit_name,
        weight: baseProduct.is_weight ? baseProduct.quantity : 0.0,
      stock: baseProduct.quantity,
      quantity: baseProduct?.quantity || 0,
      is_weight: baseProduct.is_weight,
      is_stock: baseProduct.is_stock,
        Units: baseProduct.Units,
        decrementAbility: baseProduct.number > 1,
        number: 1,
        price: newPrice,
        total: newPrice,
        code: newCode,
        rowKey: `${productId}-${unitId}`,
        baseUnit: {
          value: unitId,
          label: `${option.unitData.name} - ${newPrice}${baseProduct.unit_name}`,
          unitData: option.unitData,
        },

        selectedUnit: {
          [unitId]: {
            value: unitId,
            label: option.unitData.name,
            unitData: option.unitData,
          },
        },
      };
      return [...prevProducts, newRow];
    });
  };

  {
    /* Function To Increment The Quantity Of A Product */
  }
  const incrementQuantity = (rowKey) => {
    setSelectedProducts((prevProducts) =>
      prevProducts.map((product) => {
        if (product.rowKey === rowKey) {
          if (
            product.quantity > 0 ||
            (!product?.is_stock && !product?.is_weight)
          ) {
            const newQuantity = product.number + 1;
            return {
              ...product,
              number: newQuantity,
              canDecrement: newQuantity > 1,
              total: product.price * newQuantity,
              quantity: product.quantity - 1,
            };
          } else {
            notify("الكمية لا تسمح", "warn");
            return product;
          }
        }
        return product;
      }),
    );
  };

  {
    /* Function To Decrement The Quantity Of A Product */
  }
  const decrementQuantity = (rowKey) => {
    setSelectedProducts((prevProducts) =>
      prevProducts.map((product) => {
        if (product?.rowKey === rowKey) {
          const newQuantity = Math.max(1, product?.number - 1);
          return {
            ...product,
            number: newQuantity,
            canDecrement: newQuantity > 1,
            total: product?.price * newQuantity,
            quantity: product.quantity + 1,
          };
        }
        return product;
      }),
    );
  };

  {
    /* Function To Reset The Invoice Screen */
  }
  const handleNew = () => {
    
    let date = new Date();
    date = `${date.getFullYear()}-${date.toLocaleDateString("en-US", {
      month: "2-digit",
    })}-${date.toLocaleDateString("en-US", {
      day: "2-digit",
    })}`;
    setSelectedProducts([]);
    sessionStorage.removeItem("draftFormData");
    setDraftData(null);
    sessionStorage.removeItem("FormData");
    let backendSerial = JSON.parse(localStorage.getItem("Invoice Serial"));
    localStorage.setItem("Invoice Serial", JSON.stringify(++backendSerial));

    setFormData({
      serialInput: backendSerial || "",
      dateInput: date || "",
      clientName: "",
      notes: "",
      paymentMethod: "كاش",
      paymentMethods: {},
      invoiceType: "تيك أواى",
      phone1: "",
      newPhone: "",
      optionalPhone: "",
      address1: "",
      newAddress: "",
      optionalAddress: "",
    });
    setDraftFormData({
      serialInput: backendSerial || "",
      dateInput: date || "",
      clientName: "",
      notes: "",
      paymentMethod: "كاش",
      paymentMethods: {},
      invoiceType: "تيك أواى",
      phone1: "",
      newPhone: "",
      optionalPhone: "",
      address1: "",
      newAddress: "",
      optionalAddress: "",
    });
    setSearchClientName("");
    setSearchClientNamesResult([]);
    navigate("/", { replace: true });
    window.location.reload();
    
    
  };

  {
    /* Function To Send The Invoice To Drafts*/
  }
  const handleFreeze = async () => {
    const {
      serialInput,
      dateInput,
      clientName,
      notes,
      invoiceType,
      paymentMethod,
      address1,
      newAddress,
      optionalAddress,
      phone1,
      newPhone,
      optionalPhone,
    } = formData;
    const draft = {
      id: serialInput,
      serialInput: serialInput,
      date: dateInput,
      clientName: clientName,
      notes: notes,
      invoiceType: invoiceType,
      paymentMethod: paymentMethod,
      items: selectedProducts,
      address1: address1,
      newAddress: newAddress,
      optionalAddress: optionalAddress,
      phone1: phone1,
      newPhone: newPhone,
      optionalPhone: optionalPhone,
      time: new Date().toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
      }),
      totals: { total },
    };

    dispatch(saveDraft(draft));
  };

  {
    /* Function When Pressing Enter Inside BarCode Input */
  }
  const handleSearchEnter = (val) => {
    if (val[0].toString() === "*") {
      const regEx = /^\*[0-9]+$/;
      if (regEx.test(val) && selectedProducts.length > 0) {
        const newQuantity = Number(val.slice(1));
        const lastProduct = selectedProducts[selectedProducts.length - 1];
        if (!lastProduct.is_weight) {
          ChangeQuantity(lastProduct.rowKey, newQuantity);
        }
      }
    } else {
      let eleCode = val;

      if (val.length === 13) {
        eleCode = val?.slice(1, 7);
      }
      let result = null;
      data?.forEach((cat) => {
        cat?.products?.forEach((prod) => {
          prod?.Units?.forEach((unit) => {
            unit?.barcodes?.forEach((bc) => {
              if (
                bc?.code?.toString().toLowerCase() ===
                eleCode?.toString().toLowerCase()
              ) {
                result = { product: prod, unit };
              }
            });
          });
        });
      });
      if (!result) {
        notify("الصنف غير موجود", "warn")
        return result;
      }
      const { product, unit } = result;
      
      const newObj = {
        ...product,
        number: 1,
        weight: Number(val?.slice(7, 12)) / 1000,
        price: Number(unit.sallprice),
        total: Number(unit.sallprice),
        stock: product.quantity,
        code: unit?.barcodes[0]?.code || "",
        rowKey: `${product.id}-${unit.id}`,
        baseUnit: { value: unit.id, label: unit.name },
        selectedUnit: {
          ...product.selectedUnit,
          [unit.id]: {
            value: unit.id,
            label: unit.name,
            unitData: unit,
          },
        },
        unit: {
          id: unit?.id,
          name: unit?.name || "",
          price: Number(unit?.sallprice || 0),
          code: unit?.barcodes?.[0]?.code || "",
        },
        Units: product?.Units,
        unit_name: product?.unit_name,
        chosenUnit: {
          value: unit?.id,
          label: unit?.name,
          unitData: Number(unit?.sallprice || 0),
        },
      };
      handleAddObj(newObj);
    }
    setSearchValue("");
    focusInput();
  };

  {
    /* Function When Pressing Enter On A Product Of The Result Of The Search By Name */
  }
  const handleSearchByNameEnter = (val) => {
    if (!val) return [];
    const matches = [];
    data.forEach((cat) =>
      cat.products.forEach((prod) =>
        prod.Units.forEach((unit) => {
          if (prod.name.toLowerCase().includes(val.toLowerCase())) {
            matches.push({
              product: prod,
              unit,
              displayName: `${prod.name} - ${unit.name} - ${unit.sallprice}${prod.unit_name}`,
            });
          }
        }),
      ),
    );

    return matches;
  };

  {
    /* Function When Pressing Enter On A Product Of The Result Of The Search By Name */
  }
  const handleSearchClientName = (val) => {
    if (!val) return [];
    let matches = [];
    users?.forEach((user) => {
      if (user?.name?.toLowerCase().includes(val.toLowerCase())) {
        matches.push(user);
      }
    });
    return matches;
  };

  {
    /* Function When Pressing Enter On A Product Of The Result Of The Search By Phone */
  }
  const handleSearchClientPhone = (val) => {
    if (!val) return [];
    let matches = [];
    users?.forEach((user) => {
      if (user?.customer_info?.phone?.includes(val)) {
        matches.push(user);
      }
    });
    return matches;
  };

  {
    /* Function When Selecting A Product Of The Result Of The Search By Name */
  }
  const handleSelectProduct = ({ product, unit }) => {
    const newObj = {
      ...product,
      number: 1,
      price: Number(unit.sallprice),
      total: Number(unit.sallprice),
      code: unit?.barcodes[0]?.code || "",
      rowKey: `${product.id}-${unit.id}`,
      baseUnit: { value: unit.id, label: unit.name },
      selectedUnit: {
        [unit.id]: {
          value: unit.id,
          label: unit.name,
          unitData: unit,
        },
      },
    };
    handleAddObj(newObj);
    setSearchNameValue("");
    setSearchResults([]);
    focusInput();
  };

  {
    /* Function When Selecting A Client */
  }
  const handleSelectClient = (user) => {
    setFormData((prev) => ({
      ...prev,
      clientName: user?.name || "",
      phone1: user?.customer_info?.phone || "",
      optionalPhone: user?.customer_info?.phone2 || "",
      address1: user?.customer_info?.address1 || "",
      optionalAddress: user?.customer_info?.address2 || "",
    }));
    dispatch(setSelectedUser(user));
    sessionStorage.setItem("selectedUser", JSON.stringify(user));
    setSearchClientName(user?.name);

    setSearchClientPhone("");
    setSearchClientNamesResult([]);
    setSearchClientPhoneResult([]);
    setIsSearchPopupOpen(false);
  };

  {
    /* Function Which Enables The Keypad Inputs */
  }
  const handleKeypadInput = (value) => {
    setSearchValue(value);
  };

  {
    /* Function When Submitting A Form */
  }
  const handleSubmit = async () => {
    try {
      const isValid = await formRef?.current?.validateForm();
      if (isValid) {
        formRef?.current?.submitForm();
        navigate("/order-details");
      }
    } catch (err) {
      console.error("Validation Error:", err);
    }
  };

  return {
    data,
    inputRef,
    searchNameValueInGrid,
    selectedProducts,
    total,
    selectedCategory,
    currentPage,
    searchValue,
    searchNameValue,
    searchResults,
    inputNameRef,
    formRef,
    formData,
    draftFormData,
    draftData,
    filteredProductsByName,
    isPopupOpen,
    searchClientName,
    searchClientNamesResult,
    isSearchPopupOpen,
    searchClientPhone,
    searchClientPhoneResult,
    phoneSearchRef,
    invSerial,

    setFormData,
    setSelectedProducts,
    setSearchNameValueInGrid,
    setSelectedCategory,
    setCurrentPage,
    setSearchValue,
    setSearchNameValue,
    setSearchResults,
    setDraftData,
    setDraftFormData,
    setIsPopupOpen,
    setSearchClientName,
    setSearchClientNamesResult,
    setIsSearchPopupOpen,
    setSearchClientPhone,
    setSearchClientPhoneResult,
    setInvSerial,

    focusInput,
    ChangeQuantity,
    handleAddObj,
    handleDelete,
    handleChangeUnit,
    incrementQuantity,
    decrementQuantity,
    handleNew,
    handleFreeze,
    handleSearchEnter,
    handleSearchByNameEnter,
    handleSelectProduct,
    handleKeypadInput,
    handleSearchClientName,
    handleSelectClient,
    handleSearchClientPhone,
    handleSubmit,
  };
}
