#include "server.h"
#include <iostream>
#include "network.h"

Server::Server()
{
}

Server::~Server()
{
}

void    Server::launchServer(int port)
{
    connect(&serverSocket, SIGNAL(newConnection()), this, SLOT(newClient()));
    serverSocket.listen(QHostAddress::Any, port);

    QHostAddress addr("127.0.0.1");
    QTcpSocket test;
    test.connectToHost(addr, port);
}

void Server::newClient()
{
    //tableau de client a faire
    client = serverSocket.nextPendingConnection();
    connect(client, SIGNAL(readyRead()), this, SLOT(receiveData()));
}

void Server::receiveData()
{
    Network::receiveData(client);
}
