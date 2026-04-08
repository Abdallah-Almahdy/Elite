import React from "react";

export default function Pagination({ currentPage, totalPages, onPageChange }) {
  // Function To Go to Next Page
  const goToNextPage = () => {
    if (currentPage < totalPages) {
      onPageChange(currentPage + 1);
      onPageChange(currentPage + 1);
    }
  };

  // Function To Go to Previous Page
  const goToPrevPage = () => {
    if (currentPage > 1) {
      onPageChange(currentPage - 1);
      onPageChange(currentPage - 1);
    }
  };

  // Function To Go to a Specific Page
  const getPages = () => {
    if (totalPages <= 3) {
      return [...Array(totalPages)].map((_, i) => i + 1);
    }

    return [1, 2, 3, "...", totalPages];
  };

  return (
    <div className="flex items-center gap-2 mt-2">
      <button
        onClick={goToPrevPage}
        disabled={currentPage === 1}
        className="px-2 py-0.5 border border-gray-400 rounded-md disabled:opacity-50 hover:bg-gray-200"
      >
        السابق
      </button>

      {getPages().map((page, index) => (
        <button
          key={index}
          disabled={page === "..."}
          onClick={() => {
            if (page !== "...") {
              onPageChange(page);
              onPageChange(page);
            }
          }}
          className={`
        px-3 py-0.5 border rounded-md text-base
        ${page === "..." ? "cursor-default border-none text-gray-500" : "border-gray-400 hover:bg-gray-200"} 
        ${page === currentPage ? "bg-blue-500 text-white border-blue-500 scale-110" : ""}
      `}
        >
          {page}
        </button>
      ))}

      <button
        onClick={goToNextPage}
        disabled={currentPage === totalPages}
        className="px-2 py-0.5 border border-gray-400 rounded-md disabled:opacity-50 hover:bg-gray-200"
      >
        التالى
      </button>
    </div>
  );
}
