import React from 'react'
import { BsPrinterFill } from "react-icons/bs";
import { useUserSettingsPreference } from '../../../contexts/UserSettingsPreferenceContext';
import PermissionToggle from './PersmissionSettings/PermissionToggle';
import { MdOutlinePrintDisabled } from "react-icons/md";


export default function PrinterSettings() {
  const {printerName, setPrinterName, errors, setErrors, saveNoPrintAuth, setSaveNoPrintAuth} = useUserSettingsPreference()
  return (
      <div className='w-full'>
        <div className='flex items-center border-b'>
          <BsPrinterFill className='text-2xl text-blue-700 mr-5'/>
          <h1 className=' p-5 pr-2 font-semibold text-xl'>اعدادات الطابعة</h1>
        </div>
        <div className='flex items-center gap-x-10 p-3 text-lg py-5'>
        <h2>اسم الطابعة :</h2>
        <div className='w-[50%]'>
            <input type="text"
             name="printerName"
              id="printerName"
              className='border rounded w-full placeholder:italic placeholder:text-base px-1 focus:outline-blue-500'
             placeholder='يرجى كتابة اسم الطابعة بدقة'
             required
             value={printerName}
             onChange={(e)=>{
              const value = e.target.value;
              setPrinterName(value);
             }}
             onBlur={(e)=>{
  const value = e.target.value
  
  let printerNameError = "";

  if(value.length === 0){
    printerNameError = "يجب كتابة اسم طابعة";
  }


  setErrors({
    printerName: printerNameError,
  });
}}
             />
               {errors.printerName && (
  <p className="text-red-500 text-sm font-bold">{errors.printerName}</p>
)}
        </div>
        </div>
         <div className='flex justify-between items-center gap-x-10 text-lg p-3 pt-0'>
                     <div  className='flex items-center gap-x-1'>
                        <MdOutlinePrintDisabled className='text-xl text-blue-700'/>
                         <h2>السماح بالحفظ بدون طباعة</h2>
                     </div>
                           <div className='pt-2 sm:w-[50%]'>
                             <PermissionToggle value={saveNoPrintAuth} onChange={(val)=> setSaveNoPrintAuth(val)}/>
                           </div>
                 </div>
        <hr className='w-[95%]'/>
      </div>
    )
}
