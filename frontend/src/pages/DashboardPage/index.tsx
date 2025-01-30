import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
} from "chart.js";
import { useNavigate } from "react-router-dom";
import Profile from "../../components/Profile";

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const DashboardPage = () => {
   
  const navigate = useNavigate();

  return (
    <div>
      <Profile />
      <hr />
      <div className="card-painel" onClick={() => navigate('/ficha')}>
        <h5>Meus Treinos</h5>
        <p>
          Aqui você pode visualizar os seus ganhos e despesas do mês. Clique na seta à direita para expandir o gráfico.
        </p>
      </div>
      <div className="card-painel"  onClick={() => navigate('/aluno/modalidades')}>
        <h5>Modalidades</h5>
        <p>
          Aqui você pode visualizar os seus ganhos e despesas do mês. Clique na seta à direita para expandir o gráfico.
        </p>
      </div>
      <div className="card-painel"  onClick={() => navigate('/professores/equipe')}>
        <h5>Professores</h5>
        <p>
          Aqui você poderá conhecer toda a nossa equipe de professores.
        </p>
      </div>
    </div>
  );
};

export default DashboardPage;
