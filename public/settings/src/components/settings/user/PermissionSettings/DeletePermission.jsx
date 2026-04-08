import React, { useState } from "react";
import PermissionToggle from "./PermissionToggle";
import { FaLock } from "react-icons/fa";
import { useUserSettingsPreference } from "../../../../contexts/UserSettingsPreferenceContext";

export default function DeletePermission() {
  const {
    deleteProdAuth,
    passwordReq,
    password,
    // confirmPassword,
    errors,
    setDeleteProdAuth,
    setPasswordReq,
    setPassword,
    // setConfirmPassword,
    setErrors,
  } = useUserSettingsPreference();

  return (
    <div>
      <div className="flex justify-between items-center gap-x-10 text-lg p-3 pb-0 pt-2">
        <div className="flex items-center gap-x-1">
          <FaLock className="text-xl text-blue-700" />

          <h2>السماح بمسح منتجات </h2>
        </div>
        <div className="pt-2 sm:w-[50%]">
          <PermissionToggle
            value={deleteProdAuth}
            onChange={(val) => setDeleteProdAuth(val)}
          />
        </div>
      </div>
      <div className="p-3 pt-2">
        <div className="flex items-center me-4">
          <input
            id="inline-checkbox"
            type="checkbox"
            defaultValue=""
            checked={passwordReq}
            onChange={(e) => {
              setPasswordReq(e.target.checked);
            }}
            className="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft"
          />
          <label
            htmlFor="inline-checkbox"
            className="select-none ms-2 text-base  text-heading"
          >
            مطلوب كلمة سر
          </label>
        </div>
      </div>
      {passwordReq && (
        <div className="flex flex-col gap-y-2 p-3 pt-0">
          <div className="flex items-center gap-x-3">
            <input
              type="password"
              name="password"
              id="password"
              className="border rounded-md sm:w-[50%] px-2 py-1 focus:outline-blue-500 placeholder:italic placeholder:text-base"
              placeholder="يرجى كتابة كلمة السر"
              required={passwordReq}
              value={password}
              onChange={(e) => {
                const value = e.target.value;
                setPassword(value);
              }}
              onBlur={(e) => {
                const value = e.target.value;

                let passwordError = "";
                // let confirmError = "";

                if (passwordReq && value.length < 8) {
                  passwordError = "كلمة السر لا بد أن تكون 8 حروف على الأقل";
                }

                // if(confirmPassword && confirmPassword !== value){
                //   confirmError = "كلمة السر غير متطابقة";
                // }

                setErrors({
                  password: passwordError,
                  // confirmPassword: confirmError
                });
              }}
            />
            {errors.password && (
              <p className="text-red-500 text-sm font-bold">
                {errors.password}
              </p>
            )}
          </div>
          {/* <div className='flex items-center gap-x-3'>
             <input type="password" 
            name="confirmPassword" 
            id="confirmPassword" 
            className='border rounded-md sm:w-[50%] px-2 py-1 focus:outline-blue-500 placeholder:italic placeholder:text-base' 
            placeholder='يرجى اعادة كتابة كلمة السر'
            required={passwordReq}
             value={confirmPassword}
            onChange={(e)=>{
  const value = e.target.value;
  setConfirmPassword(value);
}}
onBlur={(e)=>{
  const value = e.target.value;
   let confirmError = "";

  if(passwordReq && value !== password){
    confirmError = "كلمة السر غير متطابقة";
  }

  setErrors(prev => ({
    ...prev,
    confirmPassword: confirmError
  }));
}}

            />
            {errors.confirmPassword && (
  <p className="text-red-500 text-sm font-bold">{errors.confirmPassword}</p>
)}
           </div> */}
        </div>
      )}
      <hr className="w-[95%] mx-auto" />
    </div>
  );
}
