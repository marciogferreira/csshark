import Api from "../../core/api";
import lista_treinos from './lista_treinos';
import { useContext, useEffect, useState } from "react";
import AuthContext from "../../contexts/AuthContext";

type Treino = {
  [key: string]: any; // As propriedades podem ser string ou number
};

export default function FichaTreinoPage() {
    
    const { user } = useContext(AuthContext);
    const[treino, setTreino] = useState<Treino>(lista_treinos);
    
    async function getTreino() {
        const response = await Api.get(`ficha-aluno/${user.email}`);
        setTreino(JSON.parse(response.data.data));
    }
  useEffect(() => {
    getTreino()
  }, [])

  return (
    <>
      <br />
          <h2>Olá, {user.name}</h2>
          {Object.keys(treino).map((name: any) => (
            <>
              <th colSpan={4}>
                <h4>{name.toUpperCase()}</h4>
              </th>
              {treino && treino[name].map((item: any, index: number) => (
                <div>
                <div className="row" key={index}>
                  <div className="col">
                    <h3>{item.exercicio}</h3>
                  </div>
                  <div className="col">
                    <h3>{item.series} X {item.reps}</h3>
                  </div>
                </div>
                <div className="row">
                  <small>Obs: {item.obs}</small>
                </div>
                <hr />
                </div>
              ))}
            </>
          ))}  
     
    </>
    )
}
