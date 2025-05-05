import { Col, Row } from 'react-bootstrap';
import Container from 'react-bootstrap/Container';
import Nav from 'react-bootstrap/Nav';
import Navbar from 'react-bootstrap/Navbar';
import NavDropdown from 'react-bootstrap/NavDropdown';
import { Link } from 'react-router-dom';

// export default function MenuDesktop() {
//     return (
//         <>
//             <Navbar expand="lg" bg="dark" data-bs-theme="dark">
//                 <Container>
//                     <Navbar.Brand href="#home">CShark</Navbar.Brand>
//                     <Navbar.Toggle aria-controls="basic-navbar-nav" />
//                     <Navbar.Collapse id="basic-navbar-nav">
//                     <Nav className="me-auto">
//                         <Nav.Link href="#home">Home</Nav.Link>
//                         <Nav.Link href="#link">Link</Nav.Link>
//                         <NavDropdown title="Dropdown" id="basic-nav-dropdown">
//                             <NavDropdown.Item href="#action/3.1">Action</NavDropdown.Item>
//                             <NavDropdown.Item href="#action/3.2">
//                                 Another action
//                             </NavDropdown.Item>
//                             <NavDropdown.Item href="#action/3.3">Something</NavDropdown.Item>
//                             <NavDropdown.Divider />
//                             <NavDropdown.Item href="#action/3.4">
//                                 Separated link
//                             </NavDropdown.Item>
//                         </NavDropdown>
//                     </Nav>
//                     </Navbar.Collapse>
//                 </Container>
//             </Navbar>
//         </>
//     )
// }

const MenuDesktop = () => {
  return (
    <Navbar bg="" expand="lg" style={{ background: '#1e97db' }}>
      <div className='container justify-content-start' style={{ display: 'flex' }}>
        <Navbar.Brand href="#" style={{  color: 'white' }}>CShark</Navbar.Brand>
        <Navbar id="basic-navbar-nav" style={{ background: 'none', color: 'white !important' }}>
          <Nav className="me-auto" style={{ color: 'white !important' }}>

            <Link to="/" className='nav-link'>Início</Link>

            <NavDropdown
              title="Cadastros"
              id="custom-dropdown"
              className="position-static"
              renderMenuOnMount={true}
            >
              <Container className="p-4" style={{ width: '500px' }}>
                <Row>
                  <Col>
                    <Link className='dropdown-item' to="/alunos">Alunos</Link>
                    <Link className='dropdown-item' to="/professores">Professores</Link>                    
                    <Link className='dropdown-item' to="/modalidades">Modalidades</Link>
                    <Link className='dropdown-item' to="/treinos">Treinos</Link>                    
                  </Col>
                  <Col>
                    <Link className='dropdown-item' to="/turmas">Turmas</Link>     
                    <Link className='dropdown-item' to="/produtos">Produtos</Link>                                   
                  </Col>
                </Row>
              </Container>

            </NavDropdown>


            <NavDropdown
              title="Secretaria"
              id="custom-dropdown"
              className="position-static"
              renderMenuOnMount={true}
            >
              <Container className="p-4" style={{ width: '500px' }}>
                <Row>
                  <Col>
                    <NavDropdown.Item href="#categoria1">Matrículas</NavDropdown.Item>
                    <NavDropdown.Item href="#categoria2">Treinos</NavDropdown.Item>
                  </Col>
                  <Col>
                  </Col>
                </Row>
              </Container>
            </NavDropdown>

            <NavDropdown
              title="Financeiro"
              id="custom-dropdown"
              className="position-static"
              renderMenuOnMount={true}
            >
              <Container className="p-4" style={{ width: '500px' }}>
                <Row>
                  <Col>
                    <NavDropdown.Item href="#categoria1">Dashboard</NavDropdown.Item>
                    <NavDropdown.Item href="#categoria2">Lançamentos</NavDropdown.Item>
                    <NavDropdown.Item href="#categoria2">Lista de Inadiplentes</NavDropdown.Item>
                    
                  </Col>
                  <Col>
                    <NavDropdown.Item href="#categoria2">Receitas</NavDropdown.Item>
                    <NavDropdown.Item href="#categoria2">Despesas</NavDropdown.Item>
                  </Col>
                </Row>
              </Container>
            </NavDropdown>

            <Nav.Link href="#contato">Contato</Nav.Link>
          </Nav>
        </Navbar>
      </div>
    </Navbar>
  );
};

export default MenuDesktop;

