import ProductCard from "./ProductCard";
import Pagination from "../common/Pagination";
import { IoClose } from "react-icons/io5";
import { useProducts } from "../../contexts/ProductsContext";

export default function ProductGrid({ userPreference }) {
  const {
    handleAddObj,
    filteredProductsByName,
    currentPage,
    setCurrentPage,
    searchNameValueInGrid,
    setSearchNameValueInGrid,
  } = useProducts();
  let productsPerPage = 8;
  if (userPreference === "textWrap") {
    productsPerPage = 16;
  } else if (userPreference === "largeWrap") {
    productsPerPage = 8;
  } else {
    productsPerPage = 12;
  }
  // Total Number of Pages
  const totalPages = Math.ceil(filteredProductsByName.length / productsPerPage);

  // To Control The Pagination
  const startIndex = (currentPage - 1) * productsPerPage;
  let visibleProducts = filteredProductsByName.slice(
    startIndex,
    startIndex + productsPerPage,
  );

  const onAddProducts = (newObj) => {
    handleAddObj(newObj);
  };

  return (
    <div className="flex h-full flex-col items-center w-full">
      <div
        className={`w-full flex justify-between items-center ${userPreference === "smallWrap" ? ` lg:mb-1` : ` mb-2`}`}
      >
        <h1 className="text-xl font-bold">الأصناف</h1>
        <div className="w-[50%] relative">
          {/* Search By Name Input */}
          <input
            type="text"
            placeholder="ابحث بالاسم"
            value={searchNameValueInGrid}
            onChange={(e) => {
              setSearchNameValueInGrid(e.target.value);
              setCurrentPage(1);
            }}
            className="border p-1 rounded-lg w-full focus:outline-blue-500"
          />
          <button
            className="absolute left-2 bottom-2"
            onClick={() => setSearchNameValueInGrid("")}
          >
            <IoClose className=" text-gray-600 text-lg" />
          </button>
        </div>
      </div>

      {/* Grid Section */}
      <div
        className={`grid grid-cols-4  gap-1 w-full ${userPreference === "textWrap" ? `grid-rows-4` : userPreference === "largeWrap" ? `grid-rows-2` : `grid-rows-3`}`}
      >
        {visibleProducts.map((product, index) => (
          <ProductCard
            key={index}
            product={product}
            onAdd={onAddProducts}
            userPreference={userPreference}
          />
        ))}
      </div>

      {/* Pagination Section */}
      <div className="flex flex-col lg:flex-row justify-between items-end w-full">
        <p className="w-full">
          يتم اظهار {visibleProducts.length} منتج من اصل{" "}
          {filteredProductsByName.length}
        </p>
        <Pagination
          currentPage={currentPage}
          totalPages={totalPages}
          onPageChange={setCurrentPage}
        />
      </div>
    </div>
  );
}
