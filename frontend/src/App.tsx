import "bootstrap/dist/css/bootstrap.min.css";
import AppRoutes from "./routes/AppRoutes";
import logo from './assets/logo.png'
import burppe from './assets/burpee.gif'
import { useContext } from "react";
import AuthContext from "./contexts/AuthContext";
function App() {

  const{loading} = useContext(AuthContext);
  return (
    <>
      <div className={`loading ${loading ? 'show' : 'hide'}`}>
        <img src={logo} width="80px" alt="" />
        <img src={burppe} width="" alt="" />
        <h4>Carregando...</h4>
      </div>
      <AppRoutes />
    </>
  )
}

export default App
