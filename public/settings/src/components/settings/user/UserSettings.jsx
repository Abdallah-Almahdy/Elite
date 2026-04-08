import React from "react";
import { useState, useEffect } from "react";
import { usePrinterSettingsPreference } from "../../../contexts/PrinterSettingsContext";
import { useSelector } from "react-redux";
import { FaUser } from "react-icons/fa";

export default function UserSettings() {
  const users = useSelector((state) => state.user?.users);
  const {
    userName,
    setUserName,
    errors,
    setErrors,
    handleSearchUserName,
    userNameResult,
    userNameShowResult,
    setUserNameResult,
    setUserNameShowResult,
    handleSelectUser,
  } = usePrinterSettingsPreference();
  const [highlightIndex, setHighlightIndex] = useState(-1);

  useEffect(() => {
    const results = handleSearchUserName(userName);
    setUserNameResult(results);
    if (results?.length > 0) {
      setUserNameShowResult(true);
    }
  }, [users, userName]);
  return (
    <div className="w-full">
      <div className="flex items-center border-b">
        <FaUser className="text-2xl text-blue-700 mr-5" />
        <h1 className=" p-5 pr-2 font-semibold text-xl">اعدادات المستخدم</h1>
      </div>
      <div className="flex items-center gap-x-10 p-3 text-lg py-5">
        <h2>اسم المستخدم :</h2>
        <div className="relative w-[50%]">
          <input
            type="text"
            name="printerName"
            id="printerName"
            className="border rounded w-full placeholder:italic placeholder:text-base px-1 focus:outline-blue-500 py-1"
            placeholder="يرجى اختيار اسم المستخدم "
            required
            value={userName}
            onBlur={(e) => {
              const value = e.target.value;

              let userNameError = "";

              if (value.length === 0) {
                userNameError = "يجب اختيار اسم المستخدم";
              }

              setErrors({
                userName: userNameError,
              });
            }}
            onChange={(e) => {
              const value = e.target.value;
              setUserName(value);
              setHighlightIndex(-1);
            }}
            onKeyDown={(e) => {
              if (e.key === "ArrowDown") {
                e.preventDefault();
                setHighlightIndex((prev) =>
                  prev < userNameResult?.length - 1 ? prev + 1 : 0,
                );
              }
              if (e.key === "ArrowUp") {
                e.preventDefault();
                setHighlightIndex((prev) =>
                  prev > 0 ? prev - 1 : userNameResult?.length - 1,
                );
              }
              if (e.key === "Enter") {
                if (highlightIndex >= 0 && userNameResult[highlightIndex]) {
                  handleSelectUser(userNameResult[highlightIndex]);
                }
              }
            }}
          />
          {userNameShowResult && userName.length > 0 && (
            <div
              //   ref={clientResultRef}
              className="border bg-white absolute w-full z-50 max-h-60 overflow-y-auto shadow"
            >
              {userNameResult?.map((user, index) => (
                <div
                  key={index}
                  className={`p-2 cursor-pointer ${
                    index === highlightIndex
                      ? "bg-blue-100"
                      : "hover:bg-gray-100"
                  }`}
                  onClick={() => {
                    handleSelectUser(user);
                  }}
                >
                  {user?.name}
                </div>
              ))}
            </div>
          )}
          {errors.userName && (
            <p className="text-red-500 text-sm font-bold">{errors.userName}</p>
          )}
        </div>
      </div>
      <hr className="w-[95%]" />
    </div>
  );
}
