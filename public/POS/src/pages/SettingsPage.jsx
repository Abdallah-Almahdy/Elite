import InvoiceType from '../components/settings/general/InvoiceTypeSettings'
import PaymentSettings from '../components/settings/general/PaymentSettings'
import TaxSettings from '../components/settings/general/TaxSettings'
import { useInvoiceSettings } from '../contexts/InvoiceSettingsContext'
import {useSettingsPreference} from "../contexts/SettingsPreferenceContext"
import { useState } from 'react'


export default function SettingsPage() {
      const [isInvoiceSettingsOpen, setIsInvoiceSettingsOpen] = useState(false);
    const {updateInvoiceSettings} = useInvoiceSettings();
    const {defaultInvoiceType,
    allowedInvoiceType,
    defaultPaymentMethod,
    applyTax,
    taxValue,
    taxType} = useSettingsPreference()
    const handleSubmit = () =>{
        const payload = {
            defaultInvoiceType: defaultInvoiceType,
    allowedInvoiceType: allowedInvoiceType,
    defaultPaymentMethod: defaultPaymentMethod,
    applyTax: applyTax,
    taxValue: taxValue,
    taxType: taxType,
        }
         updateInvoiceSettings(payload);
    }
      
    // if(!isOpen) return null;
  return (
    <div className='inset-0 z-50 bg-gray-200 w-full min-h-screen flex flex-col mx-auto lg:flex-row p-2 gap-x-10 pt-7'>
        {/* Right Section (General Settings) */}
        <div className='w-full md:w-[90%] lg:w-[50%] mx-auto  flex flex-col gap-y-3'>
            <h1 className='text-center font-bold text-2xl pb-3'>
                اعدادات الفاتورة العامة
            </h1>
            <InvoiceType />
            <PaymentSettings />
            <TaxSettings />
            <div className='w-full flex justify-end mt-10 md:mt-5 lg:mt-7 pl-5'>
            <button className=' bg-blue-700 hover:bg-blue-500 text-white rounded px-2 py-1.5'
            onClick={handleSubmit}
            > حفظ الاعدادات</button>
           </div>
        </div>
        

        {/* Left Section (User Settings) */}
         {/* <div className='w-full md:w-[90%] mx-auto lg:mx-0 lg:w-[45%] flex flex-col'>
            <h1 className='text-center font-bold text-2xl pb-6 mt-6 lg:mt-0'>
                اعدادات المستخدم
           </h1>
           <div className='bg-white flex flex-col rounded-lg shadow-lg'>
                 <PrinterSettings />
                 <h2 className=' pr-2 font-semibold text-xl pt-3'>صلاحيات المستخدم :</h2>
            
            <DiscountPermission />
            <DeletePermission />
            <PaymentChangePermission />
           </div>
           <div className='w-full flex justify-end mt-10 md:mt-5 lg:mt-7 pl-5'>
            <button className=' bg-blue-700 hover:bg-blue-500 text-white rounded px-2 py-1.5'
            onClick={()=>onSubmit({})}> حفظ الاعدادات</button>
           </div>
        </div> */}
    </div>
  )
}
