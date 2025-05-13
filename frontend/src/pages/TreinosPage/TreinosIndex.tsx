import { Select } from "../../components/FormInputs";
import Api from "../../core/api";
import lista_treinos from "./lista_treinos";
import { useEffect, useState } from "react";
import Crud from '../../components/Crud';
import Message from "../../core/Message";
import { FaPrint } from "react-icons/fa";
import { Button, Modal } from "react-bootstrap";
import Print from "../FichaTreinoPage/Print";



type Treino = {
  [key: string]: any; // As propriedades podem ser string ou number
};
const FormWrapper = ({ Field, ErrorMessage, values, setView, loadData }: any) => {
   
  const[treino, setTreino] = useState<Treino>(lista_treinos);
  const[alunos, setAlunos] = useState([]);
  const[modelosTreinos, setModelosTreinos] = useState<any>([]);

  async function getAlunos() {
    const response = await Api.get('alunos/options');
    setAlunos(response.data.data);
  }

  async function handleSubmit() {
  
    if(!values.aluno_id) {
      Message.error("Por favor, selecione o Aluno!");
      return;
    }
    
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

  async function getModelosTreinos() {
    const response = await Api.get('modelos-treinos');
    setModelosTreinos(response.data.data);
  }

   async function aplicarModelo(id: number, e:any) {
    if(e.target.checked) {
      const index = modelosTreinos.findIndex((item: any) => item.id === id);
      const treinoModelo = modelosTreinos[index];
      console.log(treinoModelo.data)
      console.log("Treino", lista_treinos)
      console.log("Modelo Treino",)
      setTreino({...lista_treinos, ...JSON.parse(treinoModelo.data)})
    } else {
      setTreino({...lista_treinos})
    }
    
  }

  useEffect(() => {
    getAlunos();
    getModelosTreinos();
    if(values.id) {
      const dataJson = JSON.parse(values.data)
      setTreino({...treino, ...dataJson});
    }
  }, [])
  
  return (
    <>
      <Field
        required
        id="aluno_id"
        label="Selecione o Aluno"
        name="aluno_id"
        placeholder="Selecione"
        list={alunos}
        disabled={values.id ? true : false}
        component={Select}
        error={<ErrorMessage name="aluno_id" />}
      />
      <br />
      <p>
        <strong>Aplicar Modelo:</strong>
      </p>
      <div className="row">
      {modelosTreinos.map((item: any, indice: number) => (
        <div className="col" key={indice}>
          <input type="checkbox" name="modelo" id={item.nome} value={item.id} onChange={(e: any) => aplicarModelo(item.id, e)} />
          &nbsp;
          <label htmlFor={item.nome}>Modelo: {item.nome}</label>
        </div>
      ))}
      </div>
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
    const [alunoId, setAlunoId] = useState<number | null>(null);

    async function printTreino(id: number) {
      setAlunoId(id)
    }

    return (
      <>
        <Modal show={alunoId != null ? true : false} onHide={() => setAlunoId(null)}>
            <Modal.Header closeButton>
              <Modal.Title>
                <h5>Impressão de Treino</h5>
              </Modal.Title>
            </Modal.Header>
            <Modal.Body>
              <Print aluno_id={alunoId} />
            </Modal.Body>
            <Modal.Footer>
              <Button variant="secondary" size="sm" onClick={() => setAlunoId(null)}>
                Fechar
              </Button>
            </Modal.Footer>
          </Modal>
        <Crud
            placeholderSearch="Pesquise pelo Nome ou CPF"
            title="Treinos de Alunos"
            endPoint="treinos"
            searchFieldName='search'
            printTreino={printTreino}
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
            fieldsHtml={({ item, printTreino } : any) => {
              return (
                <>
                  <td>{item.id}</td>
                  <td>{item.aluno.nome}</td>
                  <td>{item.aluno.cpf}</td>
                  <td>
                    <button onClick={() => printTreino(item.id)}  className="btn btn-primary btn-sm">
                      <FaPrint />
                    </button>
                  </td>
                </>
              )
            }}
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
        </>
    );
}

