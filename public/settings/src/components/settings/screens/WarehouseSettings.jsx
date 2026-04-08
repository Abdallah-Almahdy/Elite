import { FaUser } from "react-icons/fa";
import { useState, useEffect } from "react";
import { IoCloseSharp } from "react-icons/io5";

export default function WarehouseSettings({ WarehouseName, setWarehouseName }) {
  const warehouseNames = ["مخزن 1", "مخزن 2", "مخزن 3", "مخزن 4", "مخزن 5"];

  // const users = useSelector((state)=> state.user?.users);

  //   useEffect(() => {
  //           const results = handleSearchUserName(userName);
  //           setUserNameResult(results);
  //           if (results?.length > 0) {
  //             setUserNameShowResult(true);
  //           }
  //         }, [ userName]);
  const [highlightIndex, setHighlightIndex] = useState(-1);
  return (
    <div className="w-full bg-white rounded-3xl">
      <div className="flex items-center gap-x-10 p-3 text-lg py-5">
        <h2>اسم المخزن :</h2>
        <div className="relative w-[50%]">
          <select
            className="border rounded-xl w-full placeholder:text-ellipsis placeholder:text-base px-1 focus:outline-blue-500 py-1 appearance-none"
            value={WarehouseName}
            // onChange={(e) =>
            //   formik.setFieldValue("paymentMethod", e.target.value)
            // }
            onChange={(e) => {
              const value = e.target.value;
              setWarehouseName(value);
            }}
          >
            <option value="" disabled className="bg-white">
              اختر اسم المخزن
            </option>
            {warehouseNames.map((method) => (
              <option key={method} value={method} className="bg-white">
                {method}
              </option>
            ))}
          </select>
          <button
            className="absolute left-3 top-1/4"
            onClick={() => {
              setWarehouseName("");
            }}
          >
            <IoCloseSharp />
          </button>
                   
          {/* {errors.userName && (
            <p className="text-red-500 text-sm font-bold">{errors.userName}</p>
          )}  */}
        </div>
      </div>
      <hr className="w-[95%]" />
    </div>
  );
}
