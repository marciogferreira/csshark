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
import { MdDashboard } from "react-icons/md";
import { FaChalkboardTeacher } from "react-icons/fa";
// import { Alert } from "react-bootstrap";

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const DashboardPage = () => {
   
  const navigate = useNavigate();

  return (
    <div className="container-fluid">
      <Profile />
      <hr />
      {/* <h5><strong>Atenção</strong></h5> */}
      {/* <Alert key="danger" variant="danger">
          <strong>Não Haverá Aula</strong><br />
          Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat atque repudiandae id impedit. Nihil nostrum quisquam numquam assumenda. Commodi quod aliquid eum ratione, doloribus a ut. Aspernatur in minima fugit.
      </Alert> */}

      <div className="row">

        <div className="col-md-3 col-12">
          <div className="card-painel">
            <a target="_blank" href="https://api.whatsapp.com/send?phone=5585921732639&amp;text=Olá, estou com dúvidas. Tenho acesso ao App Shark, poderia me ajudar?" style={{ color: 'black', textDecoration: 'none' }}>
              <div className="row">
                <div className="col-4">
                  <img src="https://img.icons8.com/m_outlined/512/whatsapp.png"  className="img-fluid rounded mx-auto d-block" alt="..." />
                </div>
                <div className="col">
                  <p><strong>Dúvidas?</strong></p>
                  <p>
                    Aqui você pode tirar todas as suas dúvidas.
                  </p>
                </div>
              </div>   
            </a>
          </div>
        </div>

        <div className="col-md-3 col-12" onClick={() => navigate('/ficha')}>
          <div className="card-painel">
          <div className="row">
            <div className="col-4">
              <img src="https://cdn-icons-png.flaticon.com/512/5073/5073650.png" width={100} className="rounded mx-auto d-block" alt="..." />
            </div>
            <div className="col">
              <strong>Treinos</strong>
              <p>
                Aqui você pode visualizar seu treino.
              </p>
            </div>
          </div>
          </div>
        </div>

        <div className="col-md-3 col-12"  onClick={() => navigate('/aluno/modalidades')}>
          <div className="card-painel">
            <div className="row">
              <div className="col-4 justify-content-center">
                <MdDashboard size={90} />
              </div>
            <div className="col">
                <strong>Modalidades</strong>
                <p>
                  Aqui você pode visualizar as modadlidades do box e consegue realizar sua matrícula.
                </p>
            </div>
            </div>
          </div>
        </div>
        
      </div>

      <div className="row">
        <div className="col-md-3 col-12"  onClick={() => navigate('/professores/equipe')}>
          <div className="card-painel">
            <div className="row">
              <div className="col-4">
                <div className="d-flex justify-content-center">
                  <FaChalkboardTeacher size={90} />
                </div>
              </div>
              <div className="col">
                <strong>Professores</strong>
                <p>
                  Aqui você poderá conhecer toda a nossa equipe de professores.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  );
};

export default DashboardPage;
