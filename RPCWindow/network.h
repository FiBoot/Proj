#ifndef NETWORK_H
#define NETWORK_H

#include <QTcpSocket>
#include <QtNetwork>

class Network
{
public:
    static void sendFile(QString fileName, QTcpSocket * socket);
    static void receiveFile(QDataStream * input);
    static void receiveData(QTcpSocket * socket);
};

#endif // NETWORK_H
