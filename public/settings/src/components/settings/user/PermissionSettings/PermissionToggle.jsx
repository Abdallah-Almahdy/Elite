import React from "react";

export default function PermissionToggle({ value, onChange }) {
  return (
    <label className="inline-flex items-center cursor-pointer bg-gray-400 rounded-3xl">
      <input
        type="checkbox"
        className="sr-only peer"
        checked={value}
        onChange={(e) => onChange(e.target.checked)}
      />
      <div className="relative w-9 h-5 bg-neutral-quaternary peer-checked:bg-blue-700 peer-focus:ring-4 peer-focus:ring-brand-soft dark:peer-focus:ring-brand-soft rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-buffer after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand" />
    </label>
  );
}
