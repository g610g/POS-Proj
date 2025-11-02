import { initChart, updateChart } from "@/scripts/main.js";

let chart = null;
initChart().then((initChart) => {
  console.log(initChart);
  chart = initChart;
});

document.addEventListener("DOMContentLoaded", function () {
  const buttons = document.querySelectorAll(".granularity-btn");
  const selectYear = document.getElementById("select-year");
  const selectMonth = document.getElementById("select-month");
  const selectDay = document.getElementById("select-day");
  const refreshBtn = document.getElementById("chart-refresh");

  function setActive(button) {
    buttons.forEach((b) => {
      b.classList.remove("bg-blue-50");
      b.classList.remove("text-blue-700");
      b.classList.add("text-gray-600");
    });
    button.classList.add("bg-blue-50");
    button.classList.add("text-blue-700");
  }

  function showOptionsFor(mode) {
    selectYear.classList.add("hidden");
    selectMonth.classList.add("hidden");
    selectDay.classList.add("hidden");
    if (mode === "year") selectYear.classList.remove("hidden");
    if (mode === "month") {
      selectYear.classList.remove("hidden");
      selectMonth.classList.remove("hidden");
    }
    if (mode === "day") selectDay.classList.remove("hidden");
  }

  buttons.forEach((b) => {
    b.addEventListener("click", function () {
      const mode = this.getAttribute("data-mode");
      setActive(this);
      showOptionsFor(mode);
      // placeholder: call chart update
      updateChartGranularity(mode, {
        year: selectYear.value || new Date().getFullYear(),
        month: selectMonth.value || new Date().getMonth() + 1,
        day: selectDay.value || new Date().toISOString().slice(0, 10),
      });
    });
  });

  refreshBtn.addEventListener("click", function () {
    const active = document.querySelector(".granularity-btn.bg-blue-50");
    const mode = active ? active.getAttribute("data-mode") : "year";
    updateChartGranularity(mode, {
      year: selectYear.value || new Date().getFullYear(),
      month: selectMonth.value || new Date().getMonth() + 1,
      day: selectDay.value || new Date().toISOString().slice(0, 10),
    });
  });

  // Initialize default selection
  const defaultBtn = document.querySelector(
    '.granularity-btn[data-mode="year"]',
  );
  if (defaultBtn) {
    defaultBtn.click();
  }
});
export function test() {
  console.log("utils.js loaded");
}
async function updateChartGranularity(mode, opts) {
  console.log("updateChartGranularity called", mode, opts);
  updateChart(chart, mode);

  //update chart here
  // Implement actual data fetch + chart update here.
}

