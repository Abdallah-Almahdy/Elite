import React from "react";
import SearchInput from "../forms/SearchInput";
import SelectForm from "../forms/SelectForm";
import DatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css";
import { useState, useEffect } from "react";
import DraftCard from "../components/common/DraftCard";
import Pagination from "../components/common/Pagination";
import { useSelector, useDispatch } from "react-redux";

export default function DraftPage({ handleReturn }) {
  const [date, setDate] = useState(null);
  const [currentPage, setCurrentPage] = useState(1);
  const [draftsPerPage, setDraftsPerPage] = useState(9);
  const [searchByClientName, setSearchByClientName] = useState("");
  const [searchByOrder, setSearchByOrder] = useState("newest");
  const drafts = useSelector((state) => state.draft.offlineDrafts);
  useEffect(() => {
    const calculateCardsPerPage = () => {
      const screenHeight = window.innerHeight;
      const cardHeight = 50;
      const topArea = 270; 
      const availableHeight = screenHeight - topArea;
      const cards = Math.floor(availableHeight / cardHeight);
      setDraftsPerPage(cards > 0 ? cards : 1);
    };

    calculateCardsPerPage();

    window.addEventListener("resize", calculateCardsPerPage);
    return () => window.removeEventListener("resize", calculateCardsPerPage);
  }, []);

 
  const parseDraftDate = (datePart, timePart) => {
  return new Date(`${datePart} ${timePart}`);
};
const filteredDrafts = drafts
  .filter((draft) =>
    draft.clientName
      ?.toLowerCase()
      .includes(searchByClientName.toLowerCase())
  )

  .filter((draft) => {
    if (!date) return true;

    const draftDate = parseDraftDate(draft?.date, draft?.time);
    return (
      draftDate.toDateString() === date.toDateString()
    );
  })

  .sort((a, b) => {

    const dateA = parseDraftDate(a.date, a.time);
    const dateB = parseDraftDate(b.date, b.time);
    return searchByOrder === "oldest"
      ? dateA - dateB
      : dateB - dateA;
  });
   const totalPages = Math.ceil(filteredDrafts.length / draftsPerPage);
const startIndex = (currentPage - 1) * draftsPerPage;

const visibleDraft = filteredDrafts.slice(
  startIndex,
  startIndex + draftsPerPage
);

  return (
    <div className="w-full min-h-screen bg-gray-200 flex flex-col justify-start items-center">
      <div className="container w-full h-full ">
        {/*Heading*/}
        <div className="w-full flex justify-center items-end h-[15%] pt-[3rem]">
          <h1 className="w-full text-2xl font-bold text-center">المسودات</h1>
        </div>

        {/*Inputs*/}
        <div className="w-full lg:w-1/2 px-1 lg:px-0 mx-auto flex flex-col lg:flex-row justify-between items-center gap-x-1 lg:gap-x-5 gap-y-1 lg:gap-y-0 mt-5">
          <div className="w-3/4">
            <SearchInput searchByClientName={searchByClientName} setSearchByClientName={setSearchByClientName} setCurrentPage={setCurrentPage}/>
          </div>
          <div className="w-full flex sm:justify-between lg:justify-start items-center gap-x-2">
            <div className="relative max-w-sm">
              <div className="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none z-20">
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
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z"
                  />
                </svg>
              </div>

              <DatePicker
                selected={date}
                onChange={(val) => {
                  setDate(val)
                  setCurrentPage(1)
                }}
                className="block w-full ps-9 pe-3 p-3 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-lg focus:outline-blue-500 focus:ring-brand focus:border-brand px-3 py-2.5 shadow-xs placeholder:text-body"
                placeholderText="ابحث بالتاريخ"
              />
            </div>
            <div className=" w-1/2 lg:w-1/2">
              <SelectForm searchByOrder={searchByOrder} setSearchByOrder={setSearchByOrder}/>
            </div>
          </div>
        </div>

        {/*Drafts Cards*/}
        <div className="w-full px-10 pt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 grid-rows-3 gap-5">
          <DraftCard data={visibleDraft} handleReturn={handleReturn} />
        </div>

        {/*Pagination Section*/}
        <div className="w-full flex justify-center items-end">
          <Pagination
            currentPage={currentPage}
            totalPages={totalPages}
            onPageChange={(page) => setCurrentPage(page)}
          />
        </div>
      </div>
    </div>
  );
}
