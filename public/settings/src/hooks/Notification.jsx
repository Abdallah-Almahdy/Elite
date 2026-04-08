import { toast } from "react-toastify";
import { MdError } from "react-icons/md";

const notify = (msg, type) => {
  if (type === "warn") toast.warn(msg);
  else if (type === "success") toast.success(msg);
  else if (type === "error") toast.error(msg);
};

export default notify;
