<?php

//CRUD
class TarefaService
{

    private $conexao;
    private $tarefa; //Ta armazenando o Objeto Tarefa

    //Recurso de Tipagem
    public function __construct(Conexao $conexao, Tarefa $tarefa)
    {
        $this->conexao = $conexao->conectar(); //Para guardar de fato a relação de conexão ao banco
        $this->tarefa = $tarefa;
    }

    public function inserir()
    { //create
        $query =  'INSERT INTO tb_tarefas(tarefa) VALUES (:tarefa)';
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':tarefa', $this->tarefa->__get('tarefa'));
        $stmt->execute();
    }

    public function recuperar()
    { //read
        $query =  '
            SELECT 
                t.id, s.status, tarefa 
            FROM tb_tarefas AS t
            LEFT JOIN tb_status AS s ON (t.id_status = s.id)';
        $stmp = $this->conexao->prepare($query);
        $stmp->execute();
        return $stmp->fetchAll(PDO::FETCH_OBJ);
    }

    public function atualizar()
    { //update
        $query = 'UPDATE tb_tarefas SET tarefa = :tarefa WHERE id = :id';
        $stmp = $this->conexao->prepare($query);

        $stmp->bindValue(':tarefa', $this->tarefa->__get('tarefa'));
        $stmp->bindValue(':id', $this->tarefa->__get('id'));

        //Também pode ser usado no bind o ?, apenas indicando depois a ordem
        //$stmp->bindValue(':tarefa', $this->tarefa->__get('tarefa'));
        //$stmp->bindValue(':id', $this->tarefa->__get('id'));

        return $stmp->execute();
    }

    public function remover()
    { //delete
        $query = 'DELETE FROM tb_tarefas WHERE id = :id';
        $stmp = $this->conexao->prepare($query);

        $stmp->bindValue(':id', $this->tarefa->__get('id'));
        $stmp->execute();
    }

    public function marcarRealizada()
    {
        $query = 'UPDATE tb_tarefas SET id_status = :tarefa WHERE id = :id';
        $stmp = $this->conexao->prepare($query);

        $stmp->bindValue(':tarefa', $this->tarefa->__get('id_status'));
        $stmp->bindValue(':id', $this->tarefa->__get('id'));

        return $stmp->execute();
    }

    public function recuperarTarefasPendentes()
    {
        $query =  '
        SELECT 
            t.id, s.status, tarefa 
        FROM tb_tarefas AS t
            LEFT JOIN tb_status AS s ON (t.id_status = s.id)
        WHERE t.id_status = :id_status';
        $stmp = $this->conexao->prepare($query);
        $stmp->bindValue(':id_status', $this->tarefa->__get('id_status'));
        $stmp->execute();
        return $stmp->fetchAll(PDO::FETCH_OBJ);
    }
}
