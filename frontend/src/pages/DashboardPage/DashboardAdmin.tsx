import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
} from "chart.js";
import Profile from "../../components/Profile";
import { useEffect, useState } from "react";
import Api from "../../core/api";
// import { Alert } from "react-bootstrap";

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const DashboardAdmin = () => {

    const[totais, setTotais] = useState({
        total_alunos: 0,
        total_alunos_ativos: 0,
        total_alunos_inativos: 0,
        total_turmas: 0,
        total_modalidades: 0,
        total_presencas_hoje: 0
    });
   
    async function getData() {
        const response = await Api.get('dashboard/totais');
        setTotais(response.data.data)
    }
  useEffect(() => {
    getData();
  }, []);

  return (
    <div className="container-fluid">
      <Profile />
      <hr />
      
      <div className="card shadow p-4 mb-4">
      <h5 className="card-title text-primary">Resumo Geral</h5>
      <div className="row  mt-3">

        <div className="col-md-3 mb-3">
          <div className="bg-primary text-white p-3 rounded">
            <h6>Total de Alunos</h6>
            <p className="display-6">{totais.total_alunos}</p>
          </div>
        </div>

         <div className="col-md-3 mb-3">
          <div className="bg-primary text-white p-3 rounded">
            <h6>Total de Alunos (Ativos)</h6>
            <p className="display-6">{totais.total_alunos_ativos}</p>
          </div>
        </div>

          <div className="col-md-3 mb-3">
          <div className="bg-primary text-white p-3 rounded">
            <h6>Total de Alunos (Inativos)</h6>
            <p className="display-6">{totais.total_alunos_inativos}</p>
          </div>
        </div>


        <div className="col-md-3 mb-3"  >
          <div className="bg-primary text-white p-3 rounded">
            <h6>Modalidades</h6>
            <p className="display-6">{totais.total_modalidades}</p>
          </div>
        </div>

           <div className="col-md-3 mb-3"  >
          <div className="bg-primary text-white p-3 rounded">
            <h6>Turmas</h6>
            <p className="display-6">{totais.total_turmas}</p>
          </div>
        </div>

        <div className="col-md-3 mb-3">
          <div className="bg-primary text-dark p-3 rounded">
            <h6>Alunos no Cross</h6>
            <p className="display-6">0</p>
          </div>
        </div>

        <div className="col-md-3 mb-3">
          <div className="bg-primary text-white p-3 rounded">
            <h6>Presentes Hoje</h6>
            <p className="display-6">{totais.total_presencas_hoje}</p>
          </div>
        </div>


      </div>
    </div>

    

    </div>
  );
};

export default DashboardAdmin;
