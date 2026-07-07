import React from "react";

export default function SelectForm( {searchByOrder, setSearchByOrder} ) {
  return (
    <form className="max-w-sm mx-auto">
      <select
        id="searchByOrder"
        name="searchByOrder"
        value={searchByOrder}
         onChange={(e)=> {
          setSearchByOrder(e.target.value)
          console.log(searchByOrder)
         }}
        className="block w-full p-3 font-serif bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-lg focus:ring-brand focus:border-brand shadow-xs placeholder:text-body focus:outline-blue-500"
      >
        <option value="" disabled className="font-serif">
          ابحث بالأسبقية
        </option>
        <option value="newest" name="newest" className="font-serif">
          الأحدث أولا
        </option>
        <option value="oldest" name="oldest" className="font-serif">
          الأقدم أولا
        </option>
      </select>
    </form>
  );
}
