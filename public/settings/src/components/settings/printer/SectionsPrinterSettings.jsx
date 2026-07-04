import React, { useEffect, useState } from "react";
import { BsPrinterFill } from "react-icons/bs";
import { IoIosArrowDown } from "react-icons/io";
import { useDispatch, useSelector } from "react-redux";
import { fetchCategories } from "../../../store/reducers/productsSlice";
import { fetchSectionsPrintersConfig, fetchUserSectionsConfig } from "../../../store/reducers/settingSlice";
import { useScreensPermissions } from "../../../contexts/ScreensPermissionsContext";
import { FaMinusCircle } from "react-icons/fa";
import { useInvoiceSettings } from "../../../contexts/InvoiceSettingsContext";

export default function SectionsPrinterSettings({
  availablePrinters,
  sectionName,
  sectionId,
  sectionPrinterName,
  setSectionName,
  setSectionPrinterName,
  setSectionId,
  viewableSectionsPermissions,
  setViewableSectionsPermissions,
  sectionRows,
  setSectionRows,
  isUserMode,
}) {
  const categories = useSelector((state) => state?.product?.categories || []);
  const sectionsPrintersConfig = useSelector(
    (state) => state?.setting?.sectionsPrintersConfig,
  );
  const userSectionsConfig = useSelector(
    (state) => state?.setting?.userSectionsConfig,
  );
  const dispatch = useDispatch();

  const { screenSettings, setScreenSettings } = useScreensPermissions();
  const { updateInvoiceSettings } = useInvoiceSettings();
  // const saved = JSON.parse(localStorage.getItem("Invoice Settings"));
  // const hasLocalChanges =
  // saved?.sectionRows && saved.sectionRows.length > 0;

  useEffect(() => {
  dispatch(fetchCategories());
    dispatch(fetchSectionsPrintersConfig());

  if (isUserMode) {
    dispatch(fetchUserSectionsConfig());
  }
  
}, [dispatch, isUserMode]);

  const currentActiveSection =
    isUserMode && screenSettings ? screenSettings?.sectionName : sectionName;

  const allowedSectionsNames =
    isUserMode && screenSettings?.allowedSectionsNames?.length > 0
      ? screenSettings.allowedSectionsNames
      : viewableSectionsPermissions && viewableSectionsPermissions.length > 0
        ? categories.filter((c) => viewableSectionsPermissions.includes(c.id))
        : currentActiveSection
          ? categories.filter((c) => c.name === currentActiveSection)
          : [];
  const allowedSectionsNamesPOS =
    isUserMode && screenSettings?.allowedSectionsNamesPOS?.length > 0
      ? screenSettings.allowedSectionsNamesPOS
      : allowedSectionsNames && allowedSectionsNames.length > 0
        ? categories.filter((c) => allowedSectionsNames.includes(c.id))
        : currentActiveSection
          ? categories.filter((c) => c.name === currentActiveSection)
          : [];

  // useEffect(() => {
  //   const storeSectionsPrintersConfig = async () => {
  //     try {
  //       await dispatch(fetchSectionsPrintersConfig());
  //       if (sectionId && sectionsPrintersConfig) {
  //         const visibleSection = sectionsPrintersConfig?.find(
  //           (sec) => sec?.id === Number(sectionId),
  //         );
  //         if (visibleSection) {
  //           setSectionId(visibleSection?.id);
  //           setSectionName(visibleSection?.name);

  //           updateInvoiceSettings({ ...sectionRows });
  //           if (visibleSection?.printerSettings?.[0]) {
  //             setSectionPrinterName(
  //               visibleSection?.printerSettings?.[0]?.printerName,
  //             );
  //           }
  //         }
  //       }
  //     } catch (err) {
  //       console.log(err);
  //     }
  //   };
  //   storeSectionsPrintersConfig();
  // }, [sectionId]);

  useEffect(() => {
  // if (hasLocalChanges) {
  //   setSectionRows(saved.sectionRows);
  //   return;
  // }

  const filteredSections = !isUserMode || userSectionsConfig.length === 0
    ? sectionsPrintersConfig   : 
    sectionsPrintersConfig.filter((item) =>
        allowedSectionsNames.some((allowed) => allowed.id === item.id)
      )

  setSectionRows(
    filteredSections.map((item) => ({
      section_id: item.id,
      printer_name: item.printerSettings?.[0]?.printerName || "",
    }))
  );
}, [sectionsPrintersConfig]);

 useEffect(() => {
  if (isUserMode) {
    if (categories.length === 0) return;
    if(userSectionsConfig?.length === 0){
      if (!sectionsPrintersConfig?.length) return;

    setSectionRows(
      sectionsPrintersConfig.map((item) => ({
        section_id: item.id,
        printer_name: item.printerSettings?.[0]?.printerName || "",
      }))
    );
    }else{
      setScreenSettings((prev) => ({
      ...prev,
      allowedSectionsNames: categories.filter((cat) =>
        userSectionsConfig.allowdSectionsId?.includes(cat.id)
      ),
      allowedSectionsNamesPOS: categories.filter((cat) =>
        userSectionsConfig.seenSectionsId?.includes(cat.id)
      ),
    }));

    setSectionRows(
      userSectionsConfig.sectionPrinters?.map((item) => ({
        section_id: item.sectionId,
        printer_name: item.printerName,
      })) ?? []
    );
    }
    
  } else {
    if (!sectionsPrintersConfig?.length) return;

    setSectionRows(
      sectionsPrintersConfig.map((item) => ({
        section_id: item.id,
        printer_name: item.printerSettings?.[0]?.printerName || "",
      }))
    );
  }
}, [
  isUserMode,
  userSectionsConfig,
  sectionsPrintersConfig,
  categories,
]);

  // useEffect(() => {
  //   const filteredSections = isUserMode
  //     ? sectionsPrintersConfig.filter((item) =>
  //         allowedSectionsNames.some((allowed) => allowed.id === item.id),
  //       )
  //     : sectionsPrintersConfig;

  //   setSectionRows(
  //     filteredSections.map((item) => ({
  //       section_id: item.id,
  //       printer_name: item.printerSettings?.[0]?.printerName || "",
  //     })),
  //   );
  // }, [sectionsPrintersConfig]);

  const updateSection = (index, value) => {
    setSectionRows((prev) =>
      prev.map((row, i) => (i === index ? { ...row, section_id: value } : row)),
    );
  };

  const updatePrinter = (index, value) => {
    setSectionRows((prev) =>
      prev.map((row, i) =>
        i === index ? { ...row, printer_name: value } : row,
      ),
    );
  };

  const addSectionRow = () => {
    if (isUserMode && sectionRows.length >= allowedSectionsNames.length) return;
    if (!isUserMode && sectionRows.length >= categories.length) return;

    setSectionRows((prev) => [
      ...prev,
      {
        section_id: "",
        printer_name: "",
      },
    ]);
  };
  const removeSectionRow = (id) => {
    if (sectionRows.length === 1) return;

    setSectionRows((prev) =>
      prev.filter((item) => {
        return item?.section_id !== id;
      }),
    );
  };

  useEffect(()=>{
console.log(userSectionsConfig)
  }, [userSectionsConfig])

  return (
    <div className="w-full bg-white rounded-lg shadow-lg" dir="rtl">
      {isUserMode && (
        <>
          <hr className="w-[95%] mx-auto" />
          <h1 className="p-5 pr-2 font-semibold text-xl">
            صلاحيات رؤية الأقسام :
          </h1>
          <div className="flex justify-between items-center p-3 text-lg">
            <h2>الأقسام المسموح استخدامها:</h2>
            <div className="flex sm:w-[60%] flex-wrap gap-4 items-center text-base">
              {categories.map((cat) => (
                <div className="flex items-center me-4" key={cat.id}>
                  <input
                    id={`allowed-sec-${cat.id}`}
                    type="checkbox"
                    className="w-4 h-4 border rounded-xs focus:ring-2"
                    checked={allowedSectionsNames.some(
                      (item) => item.id === cat.id,
                    )}
                    onChange={(e) => {
                      if (e.target.checked) {
                        setViewableSectionsPermissions((prev) =>
                          prev?.includes(cat.id)
                            ? prev
                            : [...(prev || []), cat.id],
                        );
                        setSectionRows((prev) =>
                          prev?.some((row) => row.section_id === cat.id)
                            ? prev
                            : [
                                ...(prev || []),
                                {
                                  section_id: cat.id,
                                  printer_name: "",
                                },
                              ],
                        );

                        setScreenSettings((prev) => {
                          const currentAllowed =
                            prev?.allowedSectionsNames || [];
                          const currentAllowedPOS =
                            prev?.allowedSectionsNamesPOS || [];
                          const currentViewable =
                            prev?.viewableSectionsPermissions || [];

                          return {
                            ...prev,
                            allowedSectionsNames: currentAllowed.some(
                              (i) => i.id === cat.id,
                            )
                              ? currentAllowed
                              : [
                                  ...currentAllowed,
                                  { id: cat.id, name: cat.name },
                                ],
                            allowedSectionsNamesPOS: currentAllowedPOS.some(
                              (i) => i.id === cat.id,
                            )
                              ? currentAllowedPOS
                              : [
                                  ...currentAllowedPOS,
                                  { id: cat.id, name: cat.name },
                                ],
                            viewableSectionsPermissions:
                              currentViewable.includes(cat.id)
                                ? currentViewable
                                : [...currentViewable, cat.id],
                          };
                        });
                      } else {
                        setViewableSectionsPermissions((prev) =>
                          (prev || []).filter((id) => id !== cat.id),
                        );
                        setSectionRows((prev) =>
                          (prev || []).filter(
                            (sec) => sec?.section_id !== cat.id,
                          ),
                        );
                        setScreenSettings((prev) => ({
                          ...prev,
                          allowedSectionsNames: (
                            prev?.allowedSectionsNames || []
                          ).filter((item) => item.id !== cat.id),
                          allowedSectionsNamesPOS: (
                            prev?.allowedSectionsNamesPOS || []
                          ).filter((item) => item.id !== cat.id),
                          viewableSectionsPermissions: (
                            prev?.viewableSectionsPermissions || []
                          ).filter((v) => v !== cat.id),
                        }));
                      }
                    }}
                  />
                  <label
                    htmlFor={`allowed-sec-${cat.id}`}
                    className="select-none ms-2 text-sm font-medium cursor-pointer"
                  >
                    {cat.name}
                  </label>
                </div>
              ))}
            </div>
          </div>
          <div className="flex justify-between items-center p-3 text-lg">
            <h2>الأقسام المرئية فى شاشة البيع :</h2>
            <div className="flex sm:w-[60%] flex-wrap gap-4 items-center text-base">
              {allowedSectionsNames.map((cat) => (
                <div className="flex items-center me-4" key={cat.id}>
                  <input
                    id={`allowed-sec-${cat.id}`}
                    type="checkbox"
                    className="w-4 h-4 border rounded-xs focus:ring-2"
                    checked={allowedSectionsNamesPOS.some(
                      (item) => item.id === cat.id,
                    )}
                    onChange={(e) => {
                      if (e.target.checked) {
                        setViewableSectionsPermissions((prev) =>
                          prev?.includes(cat.id)
                            ? prev
                            : [...(prev || []), cat.id],
                        );

                        setScreenSettings((prev) => {
                          const currentAllowed =
                            prev?.allowedSectionsNamesPOS || [];

                          return {
                            ...prev,
                            allowedSectionsNamesPOS: currentAllowed.some(
                              (i) => i.id === cat.id,
                            )
                              ? currentAllowed
                              : [
                                  ...currentAllowed,
                                  { id: cat.id, name: cat.name },
                                ],
                          };
                        });
                      } else {
                        setViewableSectionsPermissions((prev) =>
                          (prev || []).filter((id) => id !== cat.id),
                        );

                        setScreenSettings((prev) => ({
                          ...prev,
                          allowedSectionsNamesPOS: (
                            prev?.allowedSectionsNamesPOS || []
                          ).filter((item) => item.id !== cat.id),
                        }));
                      }
                    }}
                  />
                  <label
                    htmlFor={`allowed-sec-${cat.id}`}
                    className="select-none ms-2 text-sm font-medium cursor-pointer"
                  >
                    {cat.name}
                  </label>
                </div>
              ))}
            </div>
          </div>
        </>
      )}

      <hr className="w-[95%]" />
      <div className="flex items-center justify-between border-b">
        <div className="flex items-center">
          <BsPrinterFill className="text-2xl text-blue-700 mr-5 ms-3" />
          <h1 className="p-5 pr-2 font-semibold text-xl">
            اعدادات طابعة الأقسام
          </h1>
        </div>
        <div className=" bg-blue-700 hover:bg-blue-500 text-white rounded px-1.5 py-1 ml-5">
          <button onClick={addSectionRow}>اضافة قسم وطابعة</button>
        </div>
      </div>
      {sectionRows.map((sec, index) => (
        <div key={index}>
          <div className="w-full flex justify-between items-center p-3 pt-5">
            <h2 className="text-xl font-semibold">القسم رقم {index + 1}</h2>
            <FaMinusCircle
              className="text-2xl bg-white  text-red-700 hover:text-red-400"
              onClick={() => {
                removeSectionRow(sec?.section_id);
              }}
            />
          </div>
          <div
            className="flex items-center gap-x-10 p-3 text-lg py-5 pt-3"
            key={index}
          >
            <div className="w-[50%] relative">
              <select
                className="w-full text-gray-900 px-2.5 py-1.5 text-base border rounded focus:outline-blue-500 appearance-none"
                value={sec?.section_id}
                onChange={(e) => {
                  const value = e.target.value;
                  const selectedCategory = categories.find(
                    (cat) => cat?.id === Number(value),
                  );
                  setSectionName(selectedCategory?.name);
                  setSectionId(Number(value));
                  updateSection(index, Number(value));
                }}
              >
                <option value="" disabled className="bg-white ">
                  اختر اسم القسم
                </option>
                {(isUserMode ? allowedSectionsNames : categories)
                  .filter(
                    (cat) =>
                      cat.id === sec.section_id ||
                      !sectionRows.some(
                        (row, i) =>
                          i !== index && Number(row.section_id) === cat.id,
                      ),
                  )
                  .map((cat) => (
                    <option key={cat.id} value={cat.id}>
                      {cat.name}
                    </option>
                  ))}
              </select>
              <div className="absolute left-2 top-2">
                <IoIosArrowDown className="text-xl text-gray-400" />
              </div>
              {/* {errors.printerName && (
  <p className="text-red-500 text-sm font-bold">{errors.printerName}</p>
)} */}
            </div>
            <div className="w-[50%] relative">
              <select
                className="w-full text-gray-900 px-2.5 py-1.5 text-base border rounded focus:outline-blue-500 appearance-none"
                value={sec?.printer_name}
                onChange={(e) => {
                  const value = e.target.value;
                  setSectionPrinterName(value);
                  updatePrinter(index, e.target.value);
                }}
              >
                <option value="" disabled className="bg-white">
                  اختر اسم الطابعة للقسم
                </option>
                {availablePrinters?.map((method) => (
                  <option key={method} value={method} className="bg-white">
                    {method}
                  </option>
                ))}
              </select>
              <div className="absolute left-2 top-2">
                <IoIosArrowDown className="text-xl text-gray-400" />
              </div>
              {/* {errors.printerName && (
  <p className="text-red-500 text-sm font-bold">{errors.printerName}</p>
)} */}
            </div>
          </div>
        </div>
      ))}
    </div>
  );
}
