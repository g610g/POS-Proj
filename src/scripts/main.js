export async function initChart() {
  const ctx = document.getElementById("salesChart");
  if (!ctx) {
    return;
  }
  const salesData = await retrieveSalesData("yearly");
  const yearsLabel = produceYearList();
  console.log(yearsLabel);

  return new Chart(ctx, {
    type: "line",
    options: {
      animation: false,
      aspectRation: 1,
    },
    data: {
      labels: yearsLabel,
      datasets: [
        {
          label: "Yearly Sale",
          data: salesData.map((salesData) => ({
            x: salesData.key,
            y: salesData.sales.length,
          })),
        },
      ],
    },
  });
}
export async function updateChart(chart, view = "daily") {
  console.log("The chart:", chart);
  switch (view) {
    case "daily":
      //fetch daily data
      const dailyData = await retrieveSalesData("daily"); //NOTE:: retrieve sales data in daily view
      console.log("daily Data", dailyData);
      const transformedDailyData = dailyData.map((daily) => ({
        x: daily.key,
        y: daily.sales.length,
      }));

      const labels = dailyData.map((d) => d.key);

      console.log(transformedDailyData);
      chart.data.labels = labels;
      chart.data.datasets[0] = {
        label: "Daily Sales",
        data: transformedDailyData,
      };
      chart.update();
      break;
    case "monthly":
      const monthlyData = await retrieveSalesData("monthly");
      const transformedMonthlyData = monthlyData.map((monthly) => ({
        x: monthly.key,
        y: monthly.sales.length,
      }));
      chart.data.labels = monthlyData.map((sales) => sales.key);
      chart.data.datasets[0] = {
        label: "Monthly Sale",
        data: transformedMonthlyData,
      };
      chart.update();
      break;

    case "yearly":
      const yearlyData = await retrieveSalesData("yearly");
      console.log(yearlyData);
      const transformedYearlyData = yearlyData.map((yearly) => ({
        x: yearly.key,
        y: yearly.sales.length,
      }));
      chart.data.labels = yearlyData.map((sales) => sales.key);

      chart.data.datasets[0] = {
        label: "Yearly Sale",
        data: transformedYearlyData,
      };
      chart.update();
      break;
  }
}
function produceYearList(year = 2025) {
  let years = [];
  for (let i = year; i >= year - 10; i--) {
    years.unshift(i);
  }
  return years;
}
function produceMonthList(locales = undefined, format = "short") {
  const year = new Date().getFullYear();
  const monthList = Array.from({ length: 12 }, (_, i) => i);
  const formatter = new Intl.DateTimeFormat(locales, { month: format });
  return monthList.map((i) => formatter.format(new Date(year, i)));
}
async function retrieveSalesData(view) {
  try {
    const response = await fetch(
      `http://localhost:8000/sales/data?view=${view}`,
    );
    const result = await response.json();
    console.log(result);
    return result;
  } catch (error) {
    alert(error);
  }
}
