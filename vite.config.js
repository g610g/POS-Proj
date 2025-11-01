import path from "path";
export default {
  root: ".",
  build: {
    outDir: "../dist",
  },
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "public/assets"), // So "@/components" points here
    },
  },
};
