<?php
App::uses('AppController', 'Controller');
class OrdersController extends AppController {

////////////////////////////////////////////////////////////

	public function admin_index() {

		$this->paginate = array(
			'recursive' => -1,
			'contain' => array(
			),
			'conditions' => array(
			),
			'order' => array(
				'Order.created' => 'DESC'
			),
			'limit' => 20,
			'paramType' => 'querystring',
		);
		$orders = $this->paginate('Order');

		$this->set(compact('orders'));
	}

////////////////////////////////////////////////////////////

	public function admin_view($id = null) {
		$order = $this->Order->find('first', array(
			'recursive' => 1,
			'conditions' => array(
				'Order.id' => $id
			)
		));
		if (empty($order)) {
			return $this->redirect(array('action'=>'index'));
		}
		$this->set(compact('order'));
	}

////////////////////////////////////////////////////////////

	public function admin_edit($id = null) {
		$this->Order->id = $id;
		if (!$this->Order->exists()) {
			throw new NotFoundException('Invalid order');
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Order->save($this->request->data)) {
				$this->Session->setFlash('The order has been saved');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The order could not be saved. Please, try again.');
			}
		} else {
			$this->request->data = $this->Order->read(null, $id);
		}
	}

////////////////////////////////////////////////////////////

	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Order->id = $id;
		if (!$this->Order->exists()) {
			throw new NotFoundException('Invalid order');
		}
		if ($this->Order->delete()) {
			$this->Session->setFlash('Order deleted');
			return $this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash('Order was not deleted');
		return $this->redirect(array('action' => 'index'));
	}

////////////////////////////////////////////////////////////

}
