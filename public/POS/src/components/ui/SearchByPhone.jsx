import React, { useEffect } from "react";
import { useState } from "react";
import { IoClose } from "react-icons/io5";
import { FiPhone } from "react-icons/fi";

export default function SearchByPhone({
  setIsSearchPopupOpen,
  searchClientPhone,
  setSearchClientPhone,
  searchClientPhoneResult,
  setSearchClientPhoneResult,
  handleSearchClientPhone,
  handleSelectClient,
  phoneSearchRef,
}) {
  const [highlightIndex, setHighlightIndex] = useState(-1);
  useEffect(() => {
    phoneSearchRef.current?.focus();
  }, []);

  useEffect(() => {
    const handleKeyDown = async (e) => {
      if (e.key === "Escape") {
        setIsSearchPopupOpen(false);
      }
    };
    window.addEventListener("keydown", handleKeyDown);
    return () => {
      window.removeEventListener("keydown", handleKeyDown);
    };
  }, [setIsSearchPopupOpen]);
  return (
    <div className="w-full min-h-screen flex justify-center items-center p-4">
      <div className="w-full h-[60vh] max-w-md bg-white rounded-xl shadow-lg p-6">
        <div className="w-full flex relative">
          <div className="flex w-3/4 justify-center items-center">
            <h1 className="text-2xl  font-bold text-center mb-6 text-gray-800">
              البحث عن عميل
            </h1>
          </div>
          <div className="w-1/4 absolute inset-y-0 left-0 flex justify-end items-center">
            <button
              className="pb-5"
              onClick={() => setIsSearchPopupOpen(false)}
            >
              <IoClose className="text-2xl " />
            </button>
          </div>
        </div>
        <hr />
        <div className="my-3">
          <h2>ابحث عن العميل باستخدام رقم الهاتف</h2>
        </div>
        <div className="relative">
          <div className="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
            <FiPhone size={20} />
          </div>
          <input
            type="text"
            name="phone1"
            id="phone1"
            ref={phoneSearchRef}
            value={searchClientPhone}
            onChange={(e) => {
              const value = e.target.value;
              setSearchClientPhone(value);
              const results = handleSearchClientPhone(value);
              setSearchClientPhoneResult(results);
              setHighlightIndex(-1);
            }}
            onKeyDown={(e) => {
              if (e.key === "ArrowDown") {
                e.preventDefault();
                setHighlightIndex((prev) =>
                  prev < searchClientPhoneResult?.length - 1 ? prev + 1 : 0,
                );
              }
              if (e.key === "ArrowUp") {
                e.preventDefault();
                setHighlightIndex((prev) =>
                  prev > 0 ? prev - 1 : searchClientPhoneResult?.length - 1,
                );
              }
              if (e.key === "Enter") {
                if (
                  highlightIndex >= 0 &&
                  searchClientPhoneResult[highlightIndex]
                ) {
                  handleSelectClient(searchClientPhoneResult[highlightIndex]);
                }
              }
            }}
            placeholder="رقم الهاتف الرئيسي"
            className="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm placeholder-gray-400"
          />
          {searchClientPhoneResult?.length > 0 && (
            <div className="border bg-white absolute w-full z-50 max-h-60 overflow-y-auto shadow">
              {searchClientPhoneResult?.slice(0, 10).map((user, index) => (
                <div
                  key={index}
                  // ref={(el)=>itemRefs.current[index]=el}
                  className={` flex justify-between items-center p-2 cursor-pointer  ${
                    index === highlightIndex
                      ? "bg-blue-100"
                      : "hover:bg-gray-100"
                  }`}
                  onClick={() => handleSelectClient(user)}
                >
                  <div>{user?.name}</div>
                  <div>{user?.customer_info?.phone}</div>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
