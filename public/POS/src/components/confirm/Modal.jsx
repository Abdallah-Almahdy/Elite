import { useState } from "react";
export default function Modal({ isOpen, onConfirm }) {
  const [selectedPreference, setSelectedPreference] = useState("textWrap");

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 w-full min-h-screen flex justify-center items-center bg-black bg-opacity-50 z-50">
      <div className="w-3/4 h-[95%] lg:h-3/4 bg-white rounded-xl flex justify-center items-center flex-col">
        <div className="w-full h-[15%] lg:h-[17%] flex justify-center flex-start lg:items-end pb-0 lg:pb-5">
          <h1 className="text-2xl font-semibold text-center">
            Choose You Preferred Settings
          </h1>
        </div>
        <div className="container w-full h-[60%] mx-auto flex flex-col lg:flex-row justify-between items-center px-10">
          {/* Third Option */}
          <button
            onClick={() => setSelectedPreference("smallWrap")}
            className={`flex flex-col items-center p-2 lg:p-5 border rounded-lg hover:transition-transform hover:scale-105 duration-300 ${
              selectedPreference === "smallWrap"
                ? "border-blue-600 border-4 shadow-md focus:outline-2 focus:outline-bg-blue-500"
                : "border-gray-300 border-4"
            }`}
          >
            <svg
              className="scale-75 h-[75px] w-[75px] lg:w-[140px] lg:h-[140px]"
              fill="#969696"
              version="1.1"
              id="XMLID_119_"
              xmlns="http://www.w3.org/2000/svg"
              xmlnsXlink="http://www.w3.org/1999/xlink"
              viewBox="0 0 24 24"
              xmlSpace="preserve"
            >
              <g id="SVGRepo_bgCarrier" strokeWidth="0"></g>
              <g
                id="SVGRepo_tracerCarrier"
                strokeLinecap="round"
                strokeLinejoin="round"
              ></g>
              <g id="SVGRepo_iconCarrier">
                {" "}
                <g id="tesxt-wrap">
                  {" "}
                  <g>
                    {" "}
                    <path d="M24,23H0v-2h24V23z M24,19H0v-2h24V19z M24,15h-7v-2h7V15z M24,11h-7V9h7V11z M24,7h-7V5h7V7z M24,3h-7V1h7V3z"></path>{" "}
                  </g>{" "}
                  <g>
                    {" "}
                    <path d="M15,15H0V1h15V15z M2,13h11V3H2V13z"></path>{" "}
                  </g>{" "}
                  <g>
                    {" "}
                    <polyline points="2.2,13 5,9 8.2,13.5 "></polyline>{" "}
                  </g>{" "}
                  <g>
                    {" "}
                    <polyline points="6.6,13 10,8 13.9,13.5 "></polyline>{" "}
                  </g>{" "}
                  <g>
                    {" "}
                    <circle cx="5" cy="6" r="2"></circle>{" "}
                  </g>{" "}
                </g>{" "}
              </g>
            </svg>
            <h2 className="text-lg font-semibold">Small Image Wrap</h2>
          </button>

          {/* Second Option */}
          <button
            onClick={() => setSelectedPreference("largeWrap")}
            className={`flex flex-col items-center p-2 lg:p-5 border rounded-lg hover:transition-transform hover:scale-105 duration-300 ${
              selectedPreference === "largeWrap"
                ? "border-blue-600 border-4 shadow-md focus:outline-2 focus:outline-bg-blue-500"
                : "border-gray-300 border-4"
            }`}
          >
            <svg
              className=" h-[75px] w-[75px] lg:w-[140px] lg:h-[140px]"
              fill="#969696"
              version="1.1"
              id="XMLID_119_"
              xmlns="http://www.w3.org/2000/svg"
              xmlnsXlink="http://www.w3.org/1999/xlink"
              viewBox="0 0 24 24"
              xmlSpace="preserve"
            >
              <g id="SVGRepo_bgCarrier" strokeWidth="0"></g>
              <g
                id="SVGRepo_tracerCarrier"
                strokeLinecap="round"
                strokeLinejoin="round"
              ></g>
              <g id="SVGRepo_iconCarrier">
                {" "}
                <g id="tesxt-wrap">
                  {" "}
                  <g>
                    {" "}
                    <path d="M24,23H0v-2h24V23z M24,19H0v-2h24V19z M24,15h-7v-2h7V15z M24,11h-7V9h7V11z M24,7h-7V5h7V7z M24,3h-7V1h7V3z"></path>{" "}
                  </g>{" "}
                  <g>
                    {" "}
                    <path d="M15,15H0V1h15V15z M2,13h11V3H2V13z"></path>{" "}
                  </g>{" "}
                  <g>
                    {" "}
                    <polyline points="2.2,13 5,9 8.2,13.5 "></polyline>{" "}
                  </g>{" "}
                  <g>
                    {" "}
                    <polyline points="6.6,13 10,8 13.9,13.5 "></polyline>{" "}
                  </g>{" "}
                  <g>
                    {" "}
                    <circle cx="5" cy="6" r="2"></circle>{" "}
                  </g>{" "}
                </g>{" "}
              </g>
            </svg>
            <h2 className="text-lg font-semibold">Large Image Wrap</h2>
          </button>

          {/* First Option */}
          <button
            onClick={() => setSelectedPreference("textWrap")}
            className={`flex flex-col items-center p-2 lg:p-5 border rounded-lg  hover:transition-transform hover:scale-105 duration-300 ${
              selectedPreference === "textWrap"
                ? "border-blue-600 border-4 shadow-md focus:outline-2 focus:outline-bg-blue-500"
                : "border-gray-300 border-4"
            }`}
          >
            <svg
              fill="#ADADAD"
              className=" h-[75px] w-[75px] lg:w-[140px] lg:h-[140px]"
              version="1.1"
              id="Capa_1"
              xmlns="http://www.w3.org/2000/svg"
              xmlnsXlink="http://www.w3.org/1999/xlink"
              viewBox="0 0 32.372 32.372"
              xmlSpace="preserve"
            >
              <g id="SVGRepo_bgCarrier" strokeWidth="0"></g>
              <g
                id="SVGRepo_tracerCarrier"
                strokeLinecap="round"
                strokeLinejoin="round"
              ></g>
              <g id="SVGRepo_iconCarrier">
                {" "}
                <g>
                  {" "}
                  <g id="c159_justified_alignment">
                    {" "}
                    <rect
                      x="0"
                      y="3.996"
                      width="32.372"
                      height="2.036"
                    ></rect>{" "}
                    <rect x="0" y="9.562" width="32.372" height="2.032"></rect>{" "}
                    <rect x="0" y="15.217" width="32.372" height="2.031"></rect>{" "}
                    <rect x="0" y="20.943" width="32.372" height="2.032"></rect>{" "}
                    <rect
                      x="0"
                      y="26.346"
                      width="32.372"
                      height="2.03"
                    ></rect>{" "}
                  </g>{" "}
                  <g id="Capa_1_79_"> </g>{" "}
                </g>{" "}
              </g>
            </svg>
            <h2 className="text-lg font-semibold">Text Wrap (Default)</h2>
          </button>
        </div>
        <div className="w-full flex justify-center items-center mt-5 lg:mt-0">
          <button
            onClick={() => {
              onConfirm(selectedPreference);
              window.location.reload();
            }}
            className="w-1/2 bg-blue-600 hover:bg-blue-500 p-2 rounded text-white text-md font-medium"
          >
            Confirm
          </button>
        </div>
      </div>
    </div>
  );
}
