import lista_treinos from "./lista_treinos";
import { useState } from "react";

export default function TreinosForm() {
  
  const[treino, setTreino] = useState(lista_treinos);
  const[nome, setNome] = useState('');
  

  async function handleSubmit() {
    console.log(treino)
  }

  async function handleTreino(name: any, id: any, item: any, value: any) {
    const lista = treino?[name] : [];
    const itemIndex = lista.findIndex((i: any) => i.id === id);
    lista[itemIndex][item] = value;
    setTreino({...treino, [name]: lista });
  }
  
  return (
      <>
        <br />
        <h3>Treinos</h3>
        <hr />
        <div className="d-flex justify-content-end mb-2">
          <button className="btn btn-primary" onClick={() => handleSubmit()}>Salvar Treino</button>
        </div>
        <div className="mb-3">
          <label>Nome</label>
          <input type="text" name="nome" value={nome} onChange={e => setNome(e.target.value)} placeholder="Nome do Treino" className="form-control" />
        </div>
        <table className="table table-hover table-striped">
          <tbody>
          {Object.keys(treino).map(name => (
            <>
              <tr>
                <th colSpan={4}>
                  <h4>{name.toUpperCase()}</h4>
                </th>
              </tr>
              {treino?[name].map((item: any, index: number) => (
                <tr key={index}>
                  <td>
                    {item.exercicio}
                  </td>
                  <td>
                    <label>
                        <input
                          placeholder="Series"
                          type="number"
                          name="series"
                          className="form-control"
                          value={item.series}
                          onChange={e => handleTreino(name, item.id, 'series', e.target.value)}
                        />
                    </label>
                  </td>
                  <td>
                    <label>
                        <input
                          placeholder="Repts"
                          type="number"
                          name="reps"
                          className="form-control"
                          value={item.reps}
                          onChange={e => handleTreino(name, item.id, 'reps', e.target.value)}
                        />
                    </label>
                  </td>
                  <td>
                    <label>
                      
                        <input
                          placeholder="Obs"
                          type="number"
                          name="obs"
                          className="form-control"
                          value={item.obs}
                          onChange={e => handleTreino(name, item.id, 'obs', e.target.value)}
                        />
                    </label>
                  </td>
                </tr>
              )): null}
            </>
          ))}  
          </tbody>
        </table>
        <div className="d-flex justify-content-end mt-2 mb-5">
          <button className="btn btn-primary" onClick={() => handleSubmit()}>Salvar Treino</button>
        </div>
      </>
  )
}