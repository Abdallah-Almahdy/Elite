export const connectQZ = async () => {
  if (qz.websocket.isActive()) return;

  qz.security.setCertificatePromise(function (resolve, reject) {
    resolve("");
  });

  qz.security.setSignaturePromise(function (toSign) {
    return function (resolve, reject) {
      resolve("");
    };
  });
 

  await qz.websocket.connect({
    host: "192.168.72.1",
    ports: { secure: 8181, insecure: 8182 },
  });
};

export const getPrinters = async () => {
  return await qz.printers.find();
};

export const printHTML = async (printerName, htmlContent) => {
  const config = qz.configs.create(printerName);

  // QZ supports HTML printing
  // const data = [
  //   {
  //     type: "html",
  //     format: "html",
  //     data: htmlContent
  //   }
  // ];
  const data = [
  '\x1B\x40',              // initialize
  'TEST PRINT\n',
  '\n\n\n',
  '\x1D\x56\x00'           // cut
];
  // const config = qz.configs.create("Microsoft Print to PDF");

  // const data = [
  //   {
  //     type: "raw",
  //     format: "plain",
  //     data: "TEST PRINT",
  //   },
  // ];

  await qz.print(config, data);
  // console.log("Sending to printer:", printerName);
  // console.log("HTML length:", htmlContent.length);
  //   await qz.print(config, data);
};
