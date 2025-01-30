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
          Aqui você pode visualizar seu treino.
        </p>
      </div>
      <div className="card-painel"  onClick={() => navigate('/aluno/modalidades')}>
        <h5>Modalidades</h5>
        <p>
          Aqui você pode visualizar as modadlidades do box e consegue realizar sua matrícula.
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
