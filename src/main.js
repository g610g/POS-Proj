import cube from "@cubejs-client/core";

const apiUrl =
  "https://heavy-lansford.gcp-us-central1.cubecloudapp.dev/cubejs-api/v1";
const cubeToken =
  "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpYXQiOjEwMDAwMDAwMDAsImV4cCI6NTAwMDAwMDAwMH0.OHZOpOBVKr-sCwn8sbZ5UFsqI3uCs6e4omT7P6WVMFw";

const cubeApi = cube(cubeToken, { apiUrl });

async function getData() {
  const acquisitionsByYearQuery = {
    dimensions: ["Artworks.yearAcquired"],
    measures: ["Artworks.count"],
    filters: [
      {
        member: "Artworks.yearAcquired",
        operator: "set",
      },
    ],
    order: {
      "Artworks.yearAcquired": "asc",
    },
  };
  const resultSet = await cubeApi.load(acquisitionsByYearQuery);

  return resultSet.tablePivot().map((row) => ({
    year: parseInt(row["Artworks.yearAcquired"]),
    count: parseInt(row["Artworks.count"]),
  }));
}
async function getDimensions() {
  const dimensionsQuery = {
    dimensions: ["Artworks.widthCm", "Artworks.heightCm"],
    measures: ["Artworks.count"],
    filters: [
      {
        member: "Artworks.classification",
        operator: "equals",
        values: ["Painting"],
      },
      {
        member: "Artworks.widthCm",
        operator: "set",
      },
      {
        member: "Artworks.widthCm",
        operator: "lt",
        values: ["500"],
      },
      {
        member: "Artworks.heightCm",
        operator: "set",
      },
      {
        member: "Artworks.heightCm",
        operator: "lt",
        values: ["500"],
      },
    ],
  };

  const resultSet = await cubeApi.load(dimensionsQuery);

  return resultSet.tablePivot().map((row) => ({
    width: parseInt(row["Artworks.widthCm"]),
    height: parseInt(row["Artworks.heightCm"]),
    count: parseInt(row["Artworks.count"]),
  }));
}
async function initChart() {
  const ctx = document.getElementById("salesChart");
  if (!ctx) {
    return;
  }
  // const data = await getData();

  getDimensions().then((dimensions) => {
    console.log(dimensions[0]);
    new Chart(ctx, {
      type: "bubble",
      options: {
        animation: false,
        aspectRation: 1,
        scales: {
          x: {
            ticks: {
              callback: (value) => `${value / 100} m`,
            },
          },
          y: {
            ticks: {
              callback: (value) => `${value / 100} m`,
            },
          },
        },
      },
      data: {
        // labels: dimensions.map((x) => x.year),
        datasets: [
          {
            label: "width == height",
            data: dimensions
              .filter((row) => row.width === row.height)
              .map((row) => ({
                x: row.width,
                y: row.height,
                r: row.count,
              })),
          },
          {
            label: "width > height",
            data: dimensions
              .filter((row) => row.width > row.height)
              .map((row) => ({
                x: row.width,
                y: row.height,
                r: row.count,
              })),
          },
          {
            label: "width < height",
            data: dimensions
              .filter((row) => row.width < row.height)
              .map((row) => ({
                x: row.width,
                y: row.height,
                r: row.count,
              })),
          },
        ],
      },
    });
  });
}
function(){


}

initChart();
