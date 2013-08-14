#ifndef SERVER_H
#define SERVER_H

#include <QTcpSocket>
#include <QtNetwork>

class Server : public QObject
{
    Q_OBJECT

public:
    Server();
    ~Server();
    void    launchServer(int port);

public slots:
    void    newClient();
    void    receiveData();

private:
    QTcpServer  serverSocket;
    QTcpSocket  *client;
};

#endif // SERVER_H
