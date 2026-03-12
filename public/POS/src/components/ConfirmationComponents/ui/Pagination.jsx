import React from "react";

export default function Pagination({ currentPage, setCurrentPage }) {
  return (
    <div className="w-[50%] mx-auto flex items-center mt-5">
      <button
        className={`w-9 h-9 rounded-full flex items-center justify-center ${currentPage >= 1 ? `bg-blue-700 font-semibold text-white` : `bg-gray-200 text-gray-600`}`}
      >
        1
      </button>
      <div
        className={`flex-1 h-2 ${currentPage > 1 ? `bg-blue-700` : `bg-gray-200`}`}
      ></div>
      <button
        className={`w-9 h-9 rounded-full font-medium flex items-center justify-center ${currentPage >= 2 ? `bg-blue-700 font-semibold text-white` : `bg-gray-200 text-gray-600`}`}
      >
        2
      </button>
      <div
        className={`flex-1 h-2 ${currentPage > 2 ? `bg-blue-700` : `bg-gray-200`}`}
      ></div>
      <button
        className={`w-9 h-9 rounded-full font-medium flex items-center justify-center ${currentPage === 3 ? `bg-blue-700 font-semibold text-white` : `bg-gray-200 text-gray-600`}`}
      >
        3
      </button>
    </div>
  );
}
