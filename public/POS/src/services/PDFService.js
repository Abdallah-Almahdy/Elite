import html2pdf from "html2pdf.js";

export const generatePDF = async (htmlElement, fileName) => {
  const opt = {
    margin: 0.2,
    filename: fileName,
    image: { type: "jpeg", quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: "in", format: "a4", orientation: "portrait" },
  };

  await html2pdf().set(opt).from(htmlElement).save();
};
