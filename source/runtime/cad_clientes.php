<?php

/*
 * X-Money - Gestao Empresarial Integrada
 *
 * Copyright (C) 2010 Eneias Ramos de Melo <neneias@gmail.com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

require_once 'edita_cliente.php';
require_once 'end_clientes.php';
require_once 'filtro_clientes.php';
require_once 'grid_clientes.php';
require_once 'impressao.php';
require_once 'classes/notebook_page.php';
require_once 'classes/toolbar.php';

class TCadClientes extends TNotebookPage
{

function __construct ()
{
    parent::__construct ('Clientes', 'clientes.png');
    
    // barra de ferramentas
    $this->pack_start ($toolbar = new TToolbar, false);
    
    $this->incluir = $toolbar->append_stock ('gtk-add', 0, array ($this, 'novo_clicked'));
    $this->alterar = $toolbar->append_stock ('gtk-edit', 1, array ($this, 'editar_clicked'));
    $this->excluir = $toolbar->append_stock ('gtk-delete', 2, array ($this, 'excluir_clicked'));
    $this->enderecos = $toolbar->append ('enderecos.png', latin1 ('Endereços'), 3, array ($this, 'enderecos_clicked'));
    $this->imprimir = $toolbar->append_stock ('gtk-print-preview', 4, array ($this, 'imprimir_clicked'));
    
    // filtro
    $this->pack_start ($this->filtro = new TFiltroClientes (array ($this, 'pega_dados')), false);
    
    // grid
    $this->pack_start ($this->grid = new TGridClientes ($this));
    
    $this->filtro->set_focus ();
    
    $this->incluir->set_sensitive (CheckPermissao ($this, 'incluir_cliente'));
    $this->alterar->set_sensitive (CheckPermissao ($this, 'alterar_cliente'));
    $this->excluir->set_sensitive (CheckPermissao ($this, 'excluir_cliente'));
    $this->imprimir->set_sensitive (CheckPermissao ($this, 'imprimir_clientes'));
    $this->enderecos->set_sensitive (CheckPermissao ($this, 'enderecos_cliente'));
}

function novo_clicked ()
{
    $edita = new TEditaCliente ($this);
    $edita->show ();
}

function imprimir_clicked ()
{
    if ($this->grid->pega_dados ()) impressao_geral ('clientes', $this->filtro->sql_print ());
}

function enderecos_clicked ()
{
    if ($this->grid->pega_dados ())
    {
	$enderecos = new TEndClientes ($this->grid->Valores [0], $this->grid->Valores [1]);
	if ($enderecos->pega_dados ()) $this->Parent->append ($enderecos);
    }
}

function editar_clicked ()
{
    if ($this->grid->pega_dados ())
    {
	$edita = new TEditaCliente ($this, 'a', $this->grid->Valores [0]);
	if ($edita->pega_dados ()) $edita->show ();
    }
}

function excluir_clicked ()
{
    if (!$this->grid->pega_dados ()) return;
    
    $id = $this->grid->Valores [0];
    
    $dialog = new Question ($this->Owner, ' Deseja mesmo remover o cliente selecionado? ');
    $resp = $dialog->ask ();
    if ($resp != Gtk::RESPONSE_YES) return;
    
    $db = new Database ($this->Owner, false);
    if (!$db->link) return;
    
    $sql = ' DELETE FROM Tb_Clientes WHERE Cod_S_Cli = ' . $id;
    if (!$db->query ($sql)) return;
    
    $this->pega_dados ();
    
    new Message ($this->Owner, 'Cliente removido com sucesso!');
}

function pega_dados ()
{
    $sql = ' SELECT * FROM Vw_Clientes ';
    $where = $this->filtro->sql ();
    
    $db = new Database ($this->Owner, true);
    if (!$db->link) return;
    
    if (!$db->multi_query ($sql . $where)) return;
    
    $this->grid->store->clear ();
    while ($line = $db->line ())
    {
	$row = $this->grid->store->append ();
	$this->grid->store->set ($row,
				 0, $line ['Id'],
				 1, $line ['Nome'],
				 2, $line ['Fantasia'],
				 3, $line ['CPF'],
				 4, $line ['IE'],
				 5, $line ['Fone'],
				 6, $line ['Fone2'],
				 7, $line ['Fax'],
                 8, $line ['Email'],
                 9, $line ['URL'],
                 10, $line ['Anotacoes'],
				 11, PointToComma ($line ['LimiteVenda']),
				 12, $line ['Ativo']);
    }
    
    $this->grid->first_line ();
    
    return true;
}

}; // TCadClientes

?>
