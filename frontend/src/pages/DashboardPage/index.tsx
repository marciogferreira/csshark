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
import { Alert } from "react-bootstrap";

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const DashboardPage = () => {
   
  const navigate = useNavigate();

  return (
    <div>
      <Profile />
      <hr />
      <h5><strong>Atenção</strong></h5>
      <Alert key="danger" variant="danger">
          <strong>Não Haverá Aula</strong><br />
          Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat atque repudiandae id impedit. Nihil nostrum quisquam numquam assumenda. Commodi quod aliquid eum ratione, doloribus a ut. Aspernatur in minima fugit.
      </Alert>
      <div className="d-flex">
        <div className="card-painel" onClick={() => navigate('/ficha')}>
          <img src="https://cdn-icons-png.flaticon.com/512/5073/5073650.png" width={100} className="rounded mx-auto d-block" alt="..." />
          <strong>Treinos</strong>
          <p>
            Aqui você pode visualizar seu treino.
          </p>
        </div>

        <div className="card-painel"  onClick={() => navigate('/aluno/modalidades')}>
        <img src="https://cdn-icons-png.flaticon.com/512/5073/5073650.png" width={100} className="rounded mx-auto d-block" alt="..." />
          <strong>Modalidades</strong>
          <p>
            Aqui você pode visualizar as modadlidades do box e consegue realizar sua matrícula.
          </p>
        </div>
        
      </div>

      <div className="d-flex">
        <div className="card-painel"  onClick={() => navigate('/professores/equipe')}>
        <img src="https://cdn-icons-png.flaticon.com/512/5073/5073650.png" width={100} className="rounded mx-auto d-block" alt="..." />
          <strong>Professores</strong>
          <p>
            Aqui você poderá conhecer toda a nossa equipe de professores.
          </p>
        </div>
      </div>

    </div>
  );
};

export default DashboardPage;
