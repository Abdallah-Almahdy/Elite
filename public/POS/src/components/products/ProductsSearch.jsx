import { useState, useEffect, useRef } from "react";
import { MdImageSearch } from "react-icons/md";
import { useProducts } from "../../contexts/ProductsContext";

export default function ProductsSearch() {
  const {
    searchValue,
    handleKeypadInput,
    inputRef,
    handleSearchEnter,
    handleSearchByNameEnter,
    searchNameValue,
    setSearchNameValue,
    inputNameRef,
    searchResults,
    setSearchResults,
    handleSelectProduct,
  } = useProducts();
  const [highlightIndex, setHighlightIndex] = useState(-1);
  const itemRefs = useRef([]);

  const changeVal = (e) => {
    handleKeypadInput(e?.target?.value);
  };

  useEffect(() => {
    if (highlightIndex >= 0 && itemRefs.current[highlightIndex]) {
      itemRefs.current[highlightIndex].scrollIntoView({
        block: "nearest",
      });
    }
  }, [highlightIndex]);

  return (
    <div className="w-full flex justify-between items-center gap-x-1">
      <div className="w-1/2">
        {/* Barcode Input */}
        <input
          type="text"
          placeholder="مسح الكود هنا... أو *10 للكمية"
          value={searchValue}
          onChange={changeVal}
          autoFocus
          className="border p-1 rounded w-full focus:outline-blue-500"
          ref={inputRef}
          onKeyDown={(e) => {
            if (e.key === "Enter") handleSearchEnter(searchValue);
          }}
        />
      </div>
      <div className="w-[50%]">
        {/* Search By Name Input */}
        <input
          ref={inputNameRef}
          type="text"
          placeholder="ابحث بالاسم"
          value={searchNameValue}
          onChange={(e) => {
            const value = e.target.value;
            setSearchNameValue(value);
            const results = handleSearchByNameEnter(value);
            setSearchResults(results);
            setHighlightIndex(-1);
          }}
          onKeyDown={(e) => {
            if (e.key === "ArrowDown") {
              e.preventDefault();
              setHighlightIndex((prev) =>
                prev < searchResults.length - 1 ? prev + 1 : 0,
              );
            }
            if (e.key === "ArrowUp") {
              e.preventDefault();
              setHighlightIndex((prev) =>
                prev > 0 ? prev - 1 : searchResults.length - 1,
              );
            }
            if (e.key === "Enter") {
              if (highlightIndex >= 0 && searchResults[highlightIndex]) {
                handleSelectProduct(searchResults[highlightIndex]);
              }
            }
          }}
          className="border p-1 rounded w-full focus:outline-blue-500"
        />
        {searchResults.length > 0 && (
          <div className="border bg-white absolute w-[30%] z-50 max-h-60 overflow-y-auto shadow">
            {searchResults.map((item, index) => (
              <div
                key={index}
                ref={(el) => (itemRefs.current[index] = el)}
                className={`p-2 cursor-pointer ${
                  index === highlightIndex ? "bg-blue-100" : "hover:bg-gray-100"
                }`}
                onClick={() => handleSelectProduct(item)}
              >
                {item.displayName}
              </div>
            ))}
          </div>
        )}
      </div>
      <div>
        <button className="border border-gray-300 rounded">
          {/* Scan Photo Input */}
          <MdImageSearch className="w-8 h-8 text-blue-500 hover:text-blue-400 " />
        </button>
      </div>
    </div>
  );
}
