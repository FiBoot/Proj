#include "client.h"
#include <iostream>
#include "network.h"

Client::Client()
{
}

Client::~Client()
{
}

void    Client::startClient(QString ip, int port)
{
    connect(&clientSocket, SIGNAL(readyRead()), this, SLOT(receiveData()));
    clientSocket.connectToHost(QHostAddress(ip), port);
}

void    Client::receiveData()
{
    Network::receiveData(&clientSocket);
}

void    Client::sendFile(QString fileName)
{
    Network::sendFile(fileName, &clientSocket);
}

void    Client::receiveFile(QString fileName)
{
    QByteArray block;
    QDataStream out(&block, QIODevice::WriteOnly);
    out.setVersion(QDataStream::Qt_5_1);
    out << (quint16)0;
    QString cmd = "receiveFile";
    out << cmd;
    out << fileName;
    out.device()->seek(0);
    out << (quint16)(block.size() - sizeof(quint16));
    if (clientSocket.write(block) == -1)
    {
        std::cerr << "Error write socket" << std::endl;
    }
}
