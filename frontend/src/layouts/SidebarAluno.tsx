import { Link } from "react-router-dom";

const SidebarAluno = ({ closeDrawer }: any) => {

  return (
    <>
      <li className="nav-item">
        <Link onClick={() => closeDrawer()} to="/ficha" className="nav-link">
            Ficha de Treino
        </Link>
      </li>   
      <li className="nav-item">
        <Link onClick={() => closeDrawer()} to="/professores/equipe" className="nav-link">
            Professores
        </Link>
      </li>    
      <li className="nav-item">
        <Link onClick={() => closeDrawer()} to="/aluno/modalidades" className="nav-link">
            Modalidades
        </Link>
      </li>    
    </>
  );
};

export default SidebarAluno;
