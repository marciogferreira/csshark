import { Link } from 'react-router-dom'

function SidebarAdmin({ closeDrawer }: any) {
    return (
        <>
            <li>
                <Link onClick={() => closeDrawer()} to="/alunos"  className="dropdown-item">
                    Alunos
                </Link>
            </li>
            <li>
                <Link onClick={() => closeDrawer()}  to="/professores" className="dropdown-item">
                    Professores
                </Link>
            </li>
            <li>
                <Link onClick={() => closeDrawer()} to="/modalidades" className="dropdown-item">
                    Modalidades
                </Link>
            </li>
            <li>
                <Link onClick={() => closeDrawer()} to="/treinos" className="dropdown-item">
                    Treinos
                </Link>
            </li>
            <li className='mb-5'>
                <Link onClick={() => closeDrawer()} to="/turmas" className="dropdown-item">
                    Turmas
                </Link>
            </li>
            <li>
                <Link onClick={() => closeDrawer()} to="/matriculas" className="dropdown-item">
                    Matrículas
                </Link>
            </li>
            <li className="nav-item ">
                <Link onClick={() => closeDrawer()} to="/financeiro" className="nav-link">
                    Financeiro
                </Link>
            </li>
            <li className='mb-5'>
                <Link onClick={() => closeDrawer()} to={"/usuarios"} className="dropdown-item">
                    Usuários
                </Link>
            </li>
        </>
    )
}
export default SidebarAdmin