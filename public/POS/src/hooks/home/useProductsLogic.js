import { useState, useEffect, useRef, useContext } from "react";
import { useSelector, useDispatch } from "react-redux";
import { useNavigate } from "react-router-dom";
import { FormDataContext } from "../../contexts/FormDataContext";
import { useSelectedProducts } from "../../contexts/SelectedProductsContext";
import { fetchCategoryProducts, saveProductsOfflineThunk, searchProductsByBarcode, searchProductsByName, searchProductsByNameInGrid } from "../../store/reducers/productSlice";
import { saveDraft } from "../../store/reducers/draftSlice";
import { setSelectedUser } from "../../store/reducers/userSlice";
import notify from "../Notification";
import { number } from "yup";
import { useUIPreferences } from "../../contexts/UIPreferencesContext";
export default function useProductsLogic() {

  const invoiceSettings = JSON.parse(localStorage.getItem("Invoice Settings"))

  const data = useSelector((state) => state?.product?.categories); // All Products Fetched From the Backend
  const products = useSelector((state) => state?.product?.products); // All Products Fetched From the Backend
  const users = useSelector((state) => state?.user?.users); // Users Fetched From the Backend
  const barcodeProduct = useSelector((state) => state?.product?.barcodeProduct); // Users Fetched From the Backend
  let filteredProductsByName = [];

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
  const [selectedCategory, setSelectedCategory] = useState(1); // State Which Hold The Value Of The Selected Category
  const [currentPage, setCurrentPage] = useState(1); // State Which Holds The Value Of The Current Page
  const [isPopupOpen, setIsPopupOpen] = useState(false);  //State Which Holds The Value of The Pop-up
  const [isSearchPopupOpen, setIsSearchPopupOpen] = useState(false);  //State Which Holds The value of The Search in The Pop-up
  const [invSerial, setInvSerial] = useState(1);  //State Which Holds The value of The Invoice Serial
  // const preference = useUIPreferences()
  const [productsPerPage, setProductsPerPage] = useState(() => {
  const uiPreference = localStorage.getItem("uiPreference");
  if (uiPreference === "textWrap") return 16;
  if (uiPreference === "smallWrap") return 12;
  return 8;
  
});
  const inputNameRef = useRef(null); // Input Ref To The Search By Name Input
  const formRef = useRef(); // Input Ref To The Form
  const inputRef = useRef(null); // Input Ref To The BarCode Input
  const phoneSearchRef = useRef(null); // Input Ref To The BarCode Input

  const navigate = useNavigate();

  const { formData, setFormData } = useContext(FormDataContext); // Form Context
  const { selectedProducts, setSelectedProducts, total } =
    useSelectedProducts(); // Selected Products Context

  useEffect(() => {
  if (selectedCategory) {
    dispatch(fetchCategoryProducts({id:selectedCategory, pageNum:currentPage, itemNum:productsPerPage}));
  }
}, [selectedCategory, dispatch]);

  {
    /* Array Of Products Filtered By Name */
  }
  if(products.length>0){
 filteredProductsByName = products
  }

 
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
          const usedByOtherUnits = selectedProducts
        .filter(p => p.id === product.id)
        .reduce((sum, p) => 
          product?.is_weight 
            ? sum + (p.weight || 0)
            : sum + (p.number || 0)
        , 0);

  
        const availableStock = product.stock - usedByOtherUnits;
              if (idx !== existsIndex) return product;

              if (newObj.weight <= availableStock) {
                            console.log(product)
                return {
                  ...product,
                  number: product.number + 1,
                  weight: product.weight + newObj.weight,
                  quantity: availableStock - newObj.weight,
                  total: (product.weight + newObj.weight) * product.price,
                  canDecrement: product.number >1,
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
                canDecrement: newObj.quantity >1,
              },
            ];
          } else {
            console.log(newObj)
            return [
              ...prevProducts,
              {
                ...newObj,
                // weight: newObj.weight,
                weight: 1,
                // quantity: newObj.quantity - newObj.weight,
                quantity: newObj.quantity - 1,
                // total: newObj.weight * newObj.price,
                total: newObj.price,
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
            total: newObj.total,
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

      let value = newVal;
      if (isNaN(value) || value < 0) {
        value = 1;
      }

      const usedByOtherUnits = prevProducts
        .filter(p => p.id === product.id && p.rowKey !== rowKey)
        .reduce((sum, p) => sum + (p.number || 0), 0);

      const availableStock = product.stock - usedByOtherUnits;

      const finalQuantity =
        !product?.is_weight && !product?.is_stock
          ? value
          : Math.min(value, availableStock);

      if (product.is_weight || product.is_stock) {
        if (value > availableStock) {
          notify("الكمية لا تسمح", "warn");
        }
      }

      return {
        ...product,
        quantity: availableStock - finalQuantity,
        number: finalQuantity,
        weight: finalQuantity,
        canDecrement: finalQuantity > 1,
        total: product.price * finalQuantity,
      };
    })
  );
};

  {
    /* Function To Change The Unit Of A Product */
  }
 
const handleChangeUnit = (productId, option, rowKey) => {
  setSelectedProducts(prevProducts => {
    const unitId = option?.unitData.id;
    const newPrice = Number(option?.unitData?.sallprice);
    const newCode = option?.unitData?.barcodes[0]?.code || "";
    const totalUsedForProduct = prevProducts
  .filter(p => p.id === productId)
  .reduce((sum, p) => sum + (p.number || 0), 0);
  console.log(totalUsedForProduct)

     const currentIndex = prevProducts.findIndex(p => p.rowKey === rowKey);

    if (currentIndex === -1) return prevProducts;

    const currentProduct = prevProducts[currentIndex];

    const existingUnitIndex = prevProducts.findIndex(
      p => p.id === productId && p.baseUnit?.value === unitId
    );
    const existingProduct = prevProducts[existingUnitIndex];
      const existNumber = existingProduct?.number;
      const currNumber = currentProduct?.number;
      const totalNumber = existNumber + currNumber;
    console.log(currentProduct.quantity)
    console.log(currentProduct.quantity)
    if (existingUnitIndex !== -1) {
      

      if(currentProduct != existingProduct){
        return prevProducts
        .filter((_, idx) => idx !== currentIndex) 
        .map((product, idx) => {
          if (idx === existingUnitIndex) {
            return {
              ...existingProduct,
              number: totalNumber,
              weight: existingProduct.is_weight ? totalNumber : existingProduct.weight,
              total: newPrice * totalNumber,
              quantity: existingProduct.stock - totalUsedForProduct,
            };
          }
          return product;
        });
      }
    }

    return prevProducts.map((product, idx) => {
      if (idx !== currentIndex) return product;

      return {
        ...product,
        number: currentProduct.number,
        weight: product.is_weight ? currentProduct.number : product.weight,
        price: newPrice,
        total: newPrice * currentProduct.number,
        code: newCode,
        rowKey: `${product.id}-${unitId}`,
        baseUnit: {
          value: unitId,
          label: `${option.unitData.name} - ${newPrice}${product.unit_name}`,
          unitData: option.unitData,
        },
        selectedUnit: {
          [unitId]: {
            value: unitId,
            label: option.unitData.name,
            unitData: option.unitData,
          },
        },
        quantity: product.stock - totalUsedForProduct,
      };
    });
  });
};



  {
    /* Function To Increment The Quantity Of A Product */
  }
 
  const incrementQuantity = (rowKey) => {
  setSelectedProducts((prevProducts) =>
    prevProducts.map((product) => {
      const usedByOtherUnits = prevProducts
        .filter(p => p.id === product.id)
        .reduce((sum, p) => 
          product?.is_weight
            ? sum + (p.weight || 0)
            : sum + (p.number || 0)
        , 0);

      const availableStock = product.stock - usedByOtherUnits;

      if (product.rowKey === rowKey) {
        let newQuantity = availableStock
        if ((availableStock > 0) || (!product?.is_stock && !product?.is_weight)) {
          if((availableStock < (product.weight + 1) && product?.weight + 1 >product?.stock) || (availableStock < (product.number + 1)  && product?.weight + 1 >product?.stock)){
             newQuantity = product?.is_weight ? product?.weight +availableStock : product?.number + availableStock;
          }else{
            newQuantity = product?.is_weight ? product?.weight +1 : product?.number + 1;
          }

          if (product.is_weight) {
            return {
              ...product,
              weight: newQuantity,
              number: newQuantity,
              canDecrement: newQuantity > 1,
              total: product.price * newQuantity,
              quantity: availableStock - 1,
            };
          }

          if (product.is_stock) {
            return {
              ...product,
              weight: newQuantity,
              number: newQuantity,
              canDecrement: newQuantity > 1,
              total: product.price * newQuantity,
              quantity: availableStock - 1,
            };
          }

          if (!product.is_stock && !product.is_weight) {
            return {
              ...product,
              number: newQuantity,
              canDecrement: newQuantity > 1,
              total: product.price * newQuantity,
              quantity: availableStock - 1,
            };
          }
        } else {
          notify("الكمية لا تسمح", "warn");
          return product;
        }
      }

      return product;
    })
  );
};

  {
    /* Function To Decrement The Quantity Of A Product */
  }
  const decrementQuantity = (rowKey) => {
    setSelectedProducts((prevProducts) =>
      prevProducts.map((product) => {
        if (product?.rowKey === rowKey) {
          const newQuantity = product.is_stock ? Math.max(1, product?.weight - 1) : Math.max(1, product?.number - 1);
          if(product.is_weight){
            return {
            ...product,
            weight: newQuantity,
            canDecrement: newQuantity > 1,
            total: product?.price * newQuantity,
            quantity: product.quantity + 1,
            number: newQuantity,
          };
          }
          if(product.is_stock){
            return {
            ...product,
            number: newQuantity,
            canDecrement: newQuantity > 1,
            total: product?.price * newQuantity,
            quantity: product.quantity + 1,
            weight:newQuantity,
          };
          }
          if(!product.is_stock && !product.is_weight){
              return {
              ...product,
              number: newQuantity,
              canDecrement: newQuantity > 1,
              total: product.price * newQuantity,
              quantity: product.quantity - 1,
            };
            }
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
      function safeJSONParse(value, fallback = null) {
  try {
    if (!value) return fallback; 
    return JSON.parse(value);
  } catch (error) {
    console.warn("Invalid JSON detected:", value);
    return fallback;
  }
}
    let backendSerial = safeJSONParse(localStorage.getItem("Invoice Serial"), null);
    localStorage.setItem("Invoice Serial", JSON.stringify(++backendSerial || 1));

    setFormData({
      serialInput: backendSerial || "",
      dateInput: date || "",
      clientName: "",
      notes: "",
      paymentMethod: invoiceSettings?.defaultPaymentMethod,
      paymentMethods: {},
      invoiceType: invoiceSettings?.defaultInvoiceType,
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
      paymentMethod: invoiceSettings?.defaultPaymentMethod,
      paymentMethods: {},
      invoiceType: invoiceSettings?.defaultInvoiceType,
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
      paymentMethods,
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
      paymentMethods: paymentMethods,
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
  const handleSearchEnter = async (val) => {
    if (val[0].toString() === "*") {
      const regEx = /^\*[0-9]+(\.[0-9]+)?$/;
      if (regEx.test(val) && selectedProducts.length > 0) {
        const newQuantity = Number(val.slice(1));
        const lastProduct = selectedProducts[selectedProducts.length - 1];
        if (!lastProduct.is_weight) {
          ChangeQuantity(lastProduct.rowKey, newQuantity);
        }
      }
    } else {
      try{
         let eleCode = val;
         let quantityCode = val;

      if (val.length === 13) {
        eleCode = val?.slice(1, 7);
        quantityCode = val?.slice(7, 12)
      }
        const resultAction = await dispatch(searchProductsByBarcode(eleCode));

const barcodeProduct = resultAction.payload.data; 
        const newObj = {
        ...barcodeProduct,
        number: 1,
        weight: Number(quantityCode) / 1000,
        price: Number(barcodeProduct?.Units?.[0]?.sallprice),
        total: Number((barcodeProduct?.Units?.[0]?.sallprice * (quantityCode / 1000) || 1)),
        stock: barcodeProduct?.quantity,
        code: barcodeProduct?.Units?.[0]?.barcodes?.[0]?.code || "",
        rowKey: `${barcodeProduct?.id}-${barcodeProduct?.Units?.[0]?.id}`,
        baseUnit: { value: barcodeProduct?.Units?.[0]?.id, label: barcodeProduct?.Units?.[0]?.name },
        selectedUnit: {
          ...barcodeProduct?.selectedUnit,
          [barcodeProduct?.Units?.[0]?.id]: {
            value: barcodeProduct?.Units?.[0]?.id,
            label: barcodeProduct?.Units?.[0]?.name,
            unitData: barcodeProduct?.Units?.[0],
          },
        },
        unit: {
          id: barcodeProduct?.Units?.[0]?.id,
          name: barcodeProduct?.Units?.[0]?.name || "",
          price: Number(barcodeProduct?.Units?.[0]?.sallprice || 0),
          code: barcodeProduct?.Units?.[0]?.barcodes?.[0]?.code || "",
        },
        Units: barcodeProduct?.Units,
        unit_name: barcodeProduct?.unit_name,
        chosenUnit: {
          value: barcodeProduct?.Units?.[0]?.id,
          label: barcodeProduct?.Units?.[0]?.name,
          unitData: Number(barcodeProduct?.Units?.[0]?.sallprice || 0),
        },
      };
      if (!barcodeProduct) {
        notify("الصنف غير موجود", "warn")
        return;
      }
      handleAddObj(newObj);
      }
      catch(err){
                notify(err, "warn")

      }
      
      
    }
    setSearchValue("");
    focusInput();
  };

  {
    /* Function When Pressing Enter On A Product Of The Result Of The Search By Name */
  }
  const handleSearchByNameEnter = (val) => {
    if (!val) return [];
    dispatch(searchProductsByName(val));
  };
  const handleSearchByNameEnterInGrid = (val) => {
    if (!val) return [];
    dispatch(searchProductsByNameInGrid(val));
  };

  {
    /* Function Returns The Result Of The Search By Name */
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
    /* Function Returns The Result Of The Search By Phone */
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
  const handleSelectProduct = ( product, unit ) => {
    const newObj = {
      ...product,
      number: 1,
      price: Number(unit.sallprice),
      total: Number(unit.sallprice),
      stock: product?.quantity,
      quantity: product?.quantity || 0,
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
    productsPerPage,

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
    setProductsPerPage,

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
    handleSearchByNameEnterInGrid,
    handleSelectProduct,
    handleKeypadInput,
    handleSearchClientName,
    handleSelectClient,
    handleSearchClientPhone,
    handleSubmit,
  };
}
