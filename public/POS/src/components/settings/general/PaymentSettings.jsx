import React from 'react'
import { BsCreditCardFill } from "react-icons/bs";
import { useSettingsPreference } from '../../../contexts/SettingsPreferenceContext';


export default function PaymentSettings() {
      const paymentMethods = ["كاش", "بطاقة ائتمان", "انستا باى", "اجل", "محفظة"];
      const {defaultPaymentMethod, setDefaultPaymentMethod} = useSettingsPreference()

   return (
    <div className='w-full bg-white rounded-lg'>
      <div className='border-b flex items-center'>
        <BsCreditCardFill className='text-2xl text-blue-700 mr-5'/>
        <h1 className=' p-5 pr-2 font-semibold text-xl'>طريقة الدفع الافتراضية</h1>
      </div>
      <div className='w-full flex flex-col justify-center items-center p-3 '>    
      <div className='w-[50%] text-lg'>
        <select
                  className="w-full text-gray-900 px-2.5 py-1.5 text-base border rounded focus:outline-blue-500 appearance-none"
                  value={defaultPaymentMethod}
                  
                  onChange={(e) => {
  const value = e.target.value;
  setDefaultPaymentMethod(value)
}}

                >
                  <option value="" disabled className="bg-white">
                    اختر طريقة الدفع
                  </option>
                  {paymentMethods.map((method) => (
                    <option key={method} value={method} className="bg-white">
                      {method}
                    </option>
                  ))}
                </select>
      </div>
      <p className='text-gray-500 text-base italic'>الطريقة الافتراضيه للفواتير الجديدة</p>
      </div>
    </div>
  )
}
