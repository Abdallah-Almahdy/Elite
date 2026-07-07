import React from 'react'
import PrinterSettings from '../components/settings/user/PrinterSettings'
import DeletePermission from '../components/settings/user/PersmissionSettings/DeletePermission'
import DiscountPermission from '../components/settings/user/PersmissionSettings/DiscountPermission'
import PaymentChangePermission from '../components/settings/user/PersmissionSettings/PaymentChangePermission'
import { useUserSettingsPreference } from '../contexts/UserSettingsPreferenceContext'
import { useState } from 'react'
import notify from '../hooks/Notification'
import { useInvoiceSettings } from '../contexts/InvoiceSettingsContext'
import UserSettings from '../components/settings/user/UserSettings'

export default function UserSettingsPage() {
  const [isUserSettingsOpen, setIsUserSettingsOpen] = useState(false);
  const {updateUserSettings} = useInvoiceSettings();
  const {
    printerName,
        priceChangeAuth,
        discountAuth,
        deleteProdAuth,
        passwordReq,
        password,
        confirmPassword,
        methodChangeAuth,
        saveNoPrintAuth,
        errors
  } = useUserSettingsPreference();

  const userSettingSubmit = () => {

    
    try{
      if(passwordReq){
        if(errors?.password?.length === 0 && errors?.confirmPassword?.length === 0){
            const payload = {
      printerName: printerName,
        priceChangeAuth: priceChangeAuth,
        discountAuth: discountAuth,
        deleteProdAuth: deleteProdAuth,
        passwordReq: passwordReq,
        password: password,
        methodChangeAuth: methodChangeAuth,
        saveNoPrintAuth: saveNoPrintAuth,
        errors: errors,
    }
        setIsUserSettingsOpen(false);
        updateUserSettings(payload);
      }
      else{
         if(errors?.password?.length > 0 || (passwordReq && password.length ===0)){
        notify("برجاء ادخال كملة سر صحيحه", "warn")
      }
      else if(errors?.confirmPassword?.length > 0  || (passwordReq && confirmPassword.length ===0)){
        notify("برجاء ادخال كلمة سر مطابقة", "warn")
      }
      else if(errors?.printerName?.length > 0 || printerName.length === 0){
        notify("برجاء ادخال اسم طابعة صحيح  ", "warn")
      }
      }
      }
      else{
        if(errors?.printerName?.length > 0 || printerName.length === 0){
        notify("برجاء ادخال اسم طابعة صحيح  ", "warn")
      }
      else{
                    const payload = {
      printerName: printerName,
        priceChangeAuth: priceChangeAuth,
        discountAuth: discountAuth,
        deleteProdAuth: deleteProdAuth,
        passwordReq: passwordReq,
        password: password,
        methodChangeAuth: methodChangeAuth,
        saveNoPrintAuth: saveNoPrintAuth,
        errors: errors,
    }
         setIsUserSettingsOpen(false);
        updateUserSettings(payload);
      }
      
    
  };
  }
    catch(err) {
     notify("يوجد مشكلة برجاء المحاولة مرة اخرى", "warn")
    }
}
  return (
    <div className='inset-0 z-50 bg-gray-200 w-full min-h-screen flex flex-col mx-auto lg:flex-row p-2 gap-x-10 pt-7 lg:pt-3'>
<div className='w-full md:w-[90%] lg:w-[45%] flex flex-col mx-auto'>
            <h1 className='text-center font-bold text-2xl pb-2 mt-6 lg:mt-0'>
                اعدادات المستخدم
           </h1>
           <div className='bg-white flex flex-col rounded-lg shadow-lg'>
            <UserSettings/>
                 <PrinterSettings />
                 <h2 className=' pr-2 font-semibold text-xl pt-3'>صلاحيات المستخدم :</h2>
            
            <DiscountPermission />
            <DeletePermission />
            <PaymentChangePermission />
           </div>
           <div className='w-full flex justify-end mt-10 md:mt-5 lg:mt-7 pl-5'>
            <button className=' bg-blue-700 hover:bg-blue-500 text-white rounded px-2 py-1.5'
            onClick={userSettingSubmit}> حفظ الاعدادات</button>
           </div>
        </div>
    </div>
    
  )
}
