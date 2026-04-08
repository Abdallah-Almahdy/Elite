import ProductCard from "./ProductCard";
import Pagination from "../common/Pagination";
import { IoClose } from "react-icons/io5";
import { useProducts } from "../../contexts/ProductsContext";
import { useEffect } from "react";
import { useSelector, useDispatch } from "react-redux";
import { fetchCategoryProducts } from "../../store/reducers/productSlice";

export default function ProductGrid({ userPreference }) {
  const dispatch = useDispatch();
  const {
    handleAddObj,
    filteredProductsByName,
    currentPage,
    setCurrentPage,
    searchNameValueInGrid,
    setSearchNameValueInGrid,
    setProductsPerPage,
    selectedCategory,
    handleSearchByNameEnterInGrid,
  } = useProducts();
  const maxPage = useSelector((state) => state?.product?.maxPage);
  const results = useSelector((state) => state?.product?.searchedResultsInGrid);
  let totalPages = maxPage;
  let productsPerPage = 8;
  if (userPreference === "textWrap") {
    productsPerPage = 16;
  } else if (userPreference === "largeWrap") {
    productsPerPage = 8;
  } else {
    productsPerPage = 12;
  }
  useEffect(() => {
    setProductsPerPage(productsPerPage);
  }, [productsPerPage]);
  // Total Number of Pages

  let filteredProducts = filteredProductsByName;

  if (searchNameValueInGrid.trim() === "") {
    filteredProducts = filteredProductsByName;
    totalPages = maxPage;
  } else {
    filteredProducts = results;
    totalPages = Math.round(results?.length / productsPerPage);
  }

  useEffect(() => {
    const timer = setTimeout(() => {
      if (searchNameValueInGrid.trim() !== "") {
        handleSearchByNameEnterInGrid(searchNameValueInGrid);
      }
    }, 300);

    return () => clearTimeout(timer);
  }, [searchNameValueInGrid]);

  //  const totalPages = Math.ceil(maxPage / productsPerPage);

  // To Control The Pagination
  const startIndex = (currentPage - 1) * productsPerPage;
  let visibleProducts;

  if (searchNameValueInGrid.trim() !== "") {
    // مفيش بحث، اعمل pagination عادي
    const startIndex = (currentPage - 1) * productsPerPage;
    visibleProducts = filteredProducts.slice(
      startIndex,
      startIndex + productsPerPage,
    );
  } else {
    // في بحث، عرض كل المنتجات اللي رجعت من السيرش بدون slice
    visibleProducts = filteredProducts;
  }

  const onAddProducts = (newObj) => {
    handleAddObj(newObj);
  };

  const handlePageChange = (page) => {
    dispatch(
      fetchCategoryProducts({
        id: selectedCategory,
        pageNum: page,
        itemNum: productsPerPage,
      }),
    );
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
              const value = e.target.value;
              setSearchNameValueInGrid(value);
              setCurrentPage(1);

              if (value.trim() === "") {
                dispatch(
                  fetchCategoryProducts({
                    id: selectedCategory,
                    pageNum: 1,
                    itemNum: productsPerPage,
                  }),
                );
              }
            }}
            className="border p-1 rounded-lg w-full focus:outline-blue-500"
          />
          <button
            className="absolute left-2 bottom-2"
            onClick={() => {
              setSearchNameValueInGrid("");
              setCurrentPage(1);

              dispatch(
                fetchCategoryProducts({
                  id: selectedCategory,
                  pageNum: 1,
                  itemNum: productsPerPage,
                }),
              );
            }}
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
          {searchNameValueInGrid.length === 0
            ? maxPage * visibleProducts.length
            : results?.length}
        </p>
        <Pagination
          currentPage={currentPage}
          totalPages={totalPages}
          onPageChange={handlePageChange}
          setCurrentPage={setCurrentPage}
        />
      </div>
    </div>
  );
}
