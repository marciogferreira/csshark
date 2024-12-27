import React from "react";
import { Bar } from "react-chartjs-2";
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
} from "chart.js";
import Sidebar from "../../layouts/SideBar";

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const DashboardPage = () => {
  // Dados para o gráfico
  const data = {
    labels: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho"],
    datasets: [
      {
        label: "Receita (R$)",
        data: [12000, 15000, 13000, 17000, 19000, 21000],
        backgroundColor: "rgba(75, 192, 192, 0.5)",
        borderColor: "rgba(75, 192, 192, 1)",
        borderWidth: 1,
      },
      {
        label: "Despesas (R$)",
        data: [8000, 9000, 7000, 10000, 11000, 9000],
        backgroundColor: "rgba(255, 99, 132, 0.5)",
        borderColor: "rgba(255, 99, 132, 1)",
        borderWidth: 1,
      },
    ],
  };

  const options = {
    responsive: true,
    plugins: {
      legend: {
        position: "top",
      },
      title: {
        display: true,
        text: "Resumo Financeiro Mensal",
      },
    },
  };

  return (
    <div>
      <h2 className="mb-4">Dashboard</h2>
      <div className="card p-3 shadow-sm">
        <Bar data={data} options={options} />
      </div>
    </div>
  );
};

export default DashboardPage;
