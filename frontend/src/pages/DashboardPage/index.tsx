import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
} from "chart.js";
import { useContext } from "react";
import AuthContext from "../../contexts/AuthContext";
import DashboardAdmin from "./DashboardAdmin";
import DashboardAluno from "./DashboardAluno";
// import { Alert } from "react-bootstrap";

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const DashboardPage = () => {
  const { user } = useContext(AuthContext);

  if(user.role == 1) {
    return <DashboardAdmin />
  }

  return <DashboardAluno />
  
};

export default DashboardPage;
