import { Select } from "../../components/FormInputs";
import Api from "../../core/api";
import lista_treinos from "./lista_treinos";
import { useEffect, useState } from "react";
import Crud from '../../components/Crud';
import Message from "../../core/Message";



type Treino = {
  [key: string]: any; // As propriedades podem ser string ou number
};
const FormWrapper = ({ Field, ErrorMessage, values, setView, loadData }: any) => {
   
  const[treino, setTreino] = useState<Treino>(lista_treinos);
  const[alunos, setAlunos] = useState([]);

  async function getAlunos() {
    const response = await Api.get('alunos/options');
    setAlunos(response.data.data);
  }

  async function handleSubmit() {
  
    if(!values.aluno_id) {
      Message.error("Por favor, selecione o Aluno!");
      return;
    }
    console.log(values)
    if(values.id) {
      await Api.put(`treinos/${values.id}`, {
        aluno_id: values.aluno_id,
        treino_id: values.treino_id,
        data: treino,
        observacao: values.observacao
      });
      Message.success("Treino Atualizado com Sucesso.")
    } else {
      const response = await Api.post('treinos', {
        aluno_id: values.aluno_id,
        data: treino,
        observacao: values.observacao
      });
      Message.success("Treino Salvo com Sucesso.")
      console.log(response)
      loadData();
      setView('list');
    }
    
  }

  async function handleTipo(name: string, value: string) {
    // treino.tipos[name] = value;
    // setTreino({...treino});
    console.log(name)
    setTreino(prev => {
      prev.tipos[name] = value;
      const lista = prev[name].map((item: any) =>{
        item.tipo = value;
        return item;
      })
      
      // prev[name] = prev[name].map((item: any) => item.tipo = value)
      return {...prev, ...{ [name]: lista}};
      
      // const lista = prev[name].map((item: any) => item.tipo = value);
      // return {...prev, ...{ [name]: lista }};
      //
    })
  }

  async function handleTreino(name: any, id: any, item: any, value: any) {
    const lista = treino[name];
    const itemIndex = lista.findIndex((i: any) => i.id === id);
    lista[itemIndex][item] = value;
    setTreino({...treino, [name]: lista });
  }

  useEffect(() => {
    getAlunos();
    if(values.id) {
      const dataJson = JSON.parse(values.data)
      setTreino({...treino, ...dataJson});
    }
  }, [])
console.log(treino)
  return (
    <>
      <Field
        required
        id="aluno_id"
        label="Selecione o Aluno"
        name="aluno_id"
        placeholder="Selecione"
        list={alunos}
        component={Select}
        error={<ErrorMessage name="aluno_id" />}
      />
      <br />
      <table className="table table-hover table-striped">
        <tbody>
          {Object.keys(treino).map((name: any) => (
            <>
              {name != 'tipos' && 
              <tr>
                <th colSpan={3}>
                  <h4>
                    {name.toUpperCase()}
                  </h4>
                </th>
                <td colSpan={3}>
                  <label htmlFor="">Tipo de Treino</label>
                  <select value={treino.tipos[name]} onChange={e => handleTipo(name, e.target.value)} name="tipo" id="tipo" className="form-control">
                    <option value="">Selecione um Tipo</option>
                    <option value="A">Tipo A</option>
                    <option value="B">Tipo B</option>
                    <option value="C">Tipo C</option>
                    <option value="D">Tipo D</option>
                    <option value="E">Tipo E</option>
                  </select>
                </td> 
                </tr>
              }

              {treino && name != 'tipos' && treino[name].map((item: any, index: number) => (
                <tr key={index}>
                  <td>
                    {item.exercicio}
                  </td>
                  <td>
                    <input type="checkbox" checked={item.show}  onChange={e => handleTreino(name, item.id, 'show', e.target.checked)} />
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
                          type="text"
                          name="obs"
                        className="form-control"
                          value={item.obs}
                          onChange={e => handleTreino(name, item.id, 'obs', e.target.value)}
                        />
                    </label>
                  </td>
                  <td>
                    <select value={item.tipo} onChange={e => handleTreino(name, item.id, 'tipo', e.target.value)} name="tipo" id="tipo" className="form-control">
                      <option value="">Selecione um Tipo</option>
                      <option value="A">Tipo A</option>
                      <option value="B">Tipo B</option>
                      <option value="C">Tipo C</option>
                      <option value="D">Tipo D</option>
                      <option value="E">Tipo E</option>
                    </select>
                  </td>
                </tr>
              ))}
            </>
          ))}  
        </tbody>
      </table>
      <div>
        <Field
          id="observacao"
          data="observacao"
          label="Observações"
          name="observacao"
          className="form-control"
          type="textarea"
          placeholder="Digite aqui as observações..."
          error={<ErrorMessage name="observacao" />}
        />
      </div>
      <div className="d-flex justify-content-end mt-2 mb-5">
        <button type="button" className="btn btn-primary" onClick={() => handleSubmit()}>Salvar Treino</button>
      </div>
    </>
)
}

export default function TreinosIndex() {
    return (
        <Crud
            title="Treinos de Alunos"
            endPoint="treinos"
            searchFieldName='search'
            emptyObject={{
                nome: '',
                aluno_id: '',
                treino_id: '',
                observacao: '',
                data: {}
            }}
            fields={[
                { name: 'id', label: 'Id', classBody: 'min-width' },
                { name: 'nome', label: 'Nome' },
                { name: 'cpf', label: 'CPF' }
            ]}
            fieldsHtml={({ item } : any) => (
              <>
                  <td>{item.id}</td>
                  <td>{item.aluno.nome}</td>
                  <td>{item.aluno.cpf}</td>
              </>
          )}
            validation={(Yup: object | any) => {
                return {
                   nome: Yup.string().required('O nome é obrigatório')
                };
            } }
            FormWrapper={FormWrapper} 
            columns={[]} 
            enableBtnNew={false} 
            
            saveContinueForm={false} 
            showActionsColumn={false} 
            showDeleteColumn={false} 
            showEditColumn={false} 
            showNewButton={false} 
            showSearch={false} 
            showPagination={false} 
            showSort={false}
            showTotal={false} 
            showViewHistory={false}
            showViewAlterStatus={false}        
          />
    );
}
