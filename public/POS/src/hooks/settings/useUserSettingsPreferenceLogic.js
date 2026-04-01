import { useState } from "react";
import { useSelector, useDispatch } from "react-redux";

export default function useUserSettingsPreferenceLogic() {

    const users = useSelector((state)=> state.user?.users);

    const [printerName, setPrinterName] = useState("");
    const [priceChangeAuth, setPriceChangeAuth] = useState(false);
    const [discountAuth, setDiscountAuth] = useState(false);
    const [deleteProdAuth, setDeleteProdAuth] = useState(false);
    const [passwordReq, setPasswordReq] = useState(false);
    const [password, setPassword] = useState("");
    const [confirmPassword, setConfirmPassword] = useState("");
    const [methodChangeAuth, setMethodChangeAuth] = useState(false);
    const [saveNoPrintAuth, setSaveNoPrintAuth] = useState(false);
    const [userName, setUserName] = useState("");
    const [userNameResult, setUserNameResult] = useState("");
    const [userNameShowResult, setUserNameShowResult] = useState(false);
    const [errors, setErrors] = useState({
        userName: "",
        printerName: "",
        password: "",
        confirmPassword: ""
      });

      
  {
    /* Function Returns The Result Of The Search By Name */
  }
  const handleSearchUserName = (val) => {
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
      /* Function When Selecting A Client */
    }
    const handleSelectUser = (user) => {
    //   dispatch(setSelectedUser(user));
      setUserName(user?.name);
  
      setUserNameResult([]);
    };

    return {
        printerName,
        priceChangeAuth,
        discountAuth,
        deleteProdAuth,
        passwordReq,
        password,
        confirmPassword,
        methodChangeAuth,
        saveNoPrintAuth,
        userName,
        errors,
        userNameResult,
        userNameShowResult,

        setPrinterName,
        setPriceChangeAuth,
        setDiscountAuth,
        setDeleteProdAuth,
        setPasswordReq,
        setPassword,
        setConfirmPassword,
        setMethodChangeAuth,
        setSaveNoPrintAuth,
        setUserName,
        setErrors,
        setUserNameResult,
        setUserNameShowResult,

        handleSearchUserName,
        handleSelectUser,
    }
  
}
