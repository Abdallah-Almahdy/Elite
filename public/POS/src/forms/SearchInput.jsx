import React from "react";

export default function SearchInput( {searchByClientName, setSearchByClientName, setCurrentPage} ) {
  return (
    <form className="w-full mx-auto">
      <label
        htmlFor="search"
        className="block mb-2.5 text-sm font-medium text-heading sr-only "
      >
        Search
      </label>
      <div className="relative">
        <div className="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
          <svg
            className="w-4 h-4 text-body"
            aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg"
            width={24}
            height={24}
            fill="none"
            viewBox="0 0 24 24"
          >
            <path
              stroke="currentColor"
              strokeLinecap="round"
              strokeWidth={2}
              d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"
            />
          </svg>
        </div>
        <input
          type="search"
          id="searchByClientName"
          name="searchByClientName"
          value={searchByClientName}
          onChange={(e)=>{
            setSearchByClientName(e.target.value);
            setCurrentPage(1);
          }}
          className="block w-full p-3 ps-9 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-lg focus:outline-blue-500 focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
          placeholder="ابحث باسم العميل"
          required=""
        />
      </div>
    </form>
  );
}
