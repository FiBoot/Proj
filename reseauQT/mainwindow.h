#ifndef MAINWINDOW_H
#define MAINWINDOW_H

#include <QMainWindow>
#include "server.h"
#include "client.h"

namespace Ui {
class MainWindow;
}

class MainWindow : public QMainWindow
{
    Q_OBJECT
    
public:
    explicit MainWindow(QWidget *parent = 0);
    ~MainWindow();

private slots:
    void on_buttonConnectionIp_clicked();
    void on_buttonLaunchServer_clicked();
    void on_buttonSendFile_clicked();
    void on_buttonReceiveFile_clicked();

private:
    Ui::MainWindow *ui;
    Server  server;
    Client  client;
    int     port;
};

#endif // MAINWINDOW_H
