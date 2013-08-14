#include "mainwindow.h"
#include "ui_mainwindow.h"

MainWindow::MainWindow(QWidget *parent) :
    QMainWindow(parent),
    ui(new Ui::MainWindow)
{
    ui->setupUi(this);
    ui->textFile->hide();
    ui->buttonReceiveFile->hide();
    ui->buttonSendFile->hide();
    port = 4242;
}

MainWindow::~MainWindow()
{
    delete ui;
}

void MainWindow::on_buttonLaunchServer_clicked()
{
    ui->buttonConnectionIp->hide();
    ui->textConnectionIp->hide();
    ui->buttonLaunchServer->hide();
    server.launchServer(port);
}

void MainWindow::on_buttonConnectionIp_clicked()
{
    ui->buttonLaunchServer->hide();
    ui->textConnectionIp->hide();
    ui->buttonConnectionIp->hide();
    ui->textFile->show();
    ui->buttonReceiveFile->show();
    ui->buttonSendFile->show();
    client.startClient(ui->textConnectionIp->text(), port);
}


void MainWindow::on_buttonSendFile_clicked()
{
    client.sendFile(ui->textFile->text());
}

void MainWindow::on_buttonReceiveFile_clicked()
{
    client.receiveFile(ui->textFile->text());
}
