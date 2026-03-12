import React from "react";

export default function Actions({
  currentPage,
  goToPrevPage,
  handleSubmit,
}) {
  return (
    <div className="w-[90%] mx-auto flex justify-between items-center gap-x-3">
      {currentPage > 1 && (
        <button
          onClick={goToPrevPage}
          type="button"
          className="text-gray-600 w-full mt-3 bg-gray-300 hover:bg-gray-200 bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-lg text-sm px-4 py-2.5 focus:outline-none"
        >
          السابق
        </button>
      )}
      <button
        onClick={handleSubmit}
        type="button"
        className="text-white w-full mt-3 bg-blue-700 hover:bg-blue-600 bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-lg text-sm px-4 py-2.5 focus:outline-none"
      >
        التالى
      </button>
    </div>
  );
}
