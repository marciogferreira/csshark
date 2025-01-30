import { Link } from "react-router-dom";

const SidebarAluno = ({ closeDrawer }: any) => {

  return (
    <>
      <li className="nav-item">
        <Link onClick={() => closeDrawer()} to="/ficha" className="nav-link">
            Ficha de Treino
        </Link>
      </li>      
    </>
  );
};

export default SidebarAluno;
