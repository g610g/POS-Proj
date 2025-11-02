import path from "path";
export default {
  root: ".",
  build: {
    outDir: "../dist",
  },
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "src"), // So "@/components" points here
    },
  },
};
